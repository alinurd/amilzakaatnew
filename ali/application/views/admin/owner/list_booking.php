<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo $title; ?></h3>
        </div>
    </div><!-- /.box-header -->

    <!-- include message block -->
    <div class="col-sm-12">
        <?php $this->load->view('admin/includes/_messages'); ?>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable" id="cs_datatable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr role="row">
                                <th width="20"><?php echo trans('id'); ?></th>
                                <th>Code Transaksi</th>
                                <th>Group</th>
                                <th>Nama Customer</th>
                                <th>Nama Event</th>
                                <th>Tanggal Acara</th>
                                <th>Harga Rp.</th>
                                <th>Masuk Rp.</th>
                                <th>Status</th>
                                <th class="max-width-120"><?php echo trans('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($data as $k) :
                            ?>
                                <tr>
                                    <td><?php echo html_escape($k->id_booking); ?></td>
                                    <td><?php echo html_escape($k->code); ?></td>
                                    <td><?php echo html_escape($k->group); ?></td>
                                    <td><?php echo html_escape($k->nama); ?></td>
                                    <td><?php echo html_escape($k->title); ?></td>
                                    <td><?php echo html_escape($k->tanggal_acara); ?></td>
                                    <td class="pull-right"><b><?php echo $k->harga !== null && $k->harga !== 0 ? number_format($k->harga) : '0'; ?></b></td>

                                    <td>
                                     <?php
                                    $this->db->where('id_booking', $k->id_booking);
                                    $query = $this->db->get("bayar");
                                    $byr= $query->result();
                                    $tM = 0;
                                    foreach ($byr as $item) {
                                        $tM += $item->jumlah;
                                    }
                                   ?><b class="pull-right"><?php echo $tM !== null && $tM !== 0 ? number_format($tM) : '0'; ?></b><br>
                                     <center>
                                     ( <?=count($byr)?> x Bayar )
                                    <br>
                                <a href="">Detail</a>
                                     </center>
                            </td>

                                    <td>
                                        <?php if ($k->status_booking == 1) : ?>
                                            <label class="label bg-info    ">Pending</label>
                                        <?php elseif ($k->status_booking == 2) : ?>
                                            <label class="label label-warning">Proses Pembayaran</label>
                                        <?php else : ?>
                                            <label class="label label-success">Lunas</label>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <button type="button" class="btn bg-purple" data-toggle="modal" data-target="#myModal<?= $k->id_booking ?>" onclick="$('#modal_user_id').val('<?php echo html_escape($k->id_booking); ?>');">
                                            <i class="glyphicon glyphicon-usd  option-icon"></i>Update Pembayaran
                                        </button>
                                        <?php echo form_open('owner_controller/booking_options_post'); ?>
                                        <input type="hidden" name="id" value="<?php echo html_escape($k->id_booking); ?>">
                                        <?php echo form_close(); ?><!-- form end -->
                                    </td>

                                </tr>
                                <div id="myModal<?= $k->id_booking ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tenor Pembayaran [<?= $k->title ?> - <?= $k->nama ?>]</h4>
            </div>
            <?php echo form_open('owner_controller/update_pembayaran'); ?>
            <input type="hidden" name="id_booking" id="modal_user_id" value="<?= $k->id_booking ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label for="jml" class="">jumlah Pembayaran </label>
                    <input type="number" id="jml" name="jumlah" class="form-control">
                </div>
                <div class="form-group">
                    <label class="control-label">Tenor Pembayaran :</label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-md" id="tenoskurang<?= $k->id_booking ?>" type="button">-</button>
                        </span>
                        <input type="hidden" id="countTenor<?= $k->id_booking ?>" value="<?php echo count($byr) > 0 ? count($byr) : '1'; ?>">
                        <input id="tenorInput<?= $k->id_booking ?>" type="text" readonly class="form-control text-center col-md-6" name="tenor" placeholder="Tenor Pembayaran" value="<?php echo count($byr) > 0 ? count($byr) : '0'; ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-md" id="tenortambah<?= $k->id_booking ?>" type="button">+</button>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><?php echo trans('save'); ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('close'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
            <!-- form end -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var value = parseInt($('#tenorInput<?= $k->id_booking ?>').val()) || 0;
            var tenor = parseInt($('#countTenor<?= $k->id_booking ?>').val()) || 0;
            // alert(value)
            $('#tenorInput<?= $k->id_booking ?>').val(value + 1);
            
        // $('#tenortambah<?= $k->id_booking ?>').click(function() {
        //     var value = parseInt($('#tenorInput<?= $k->id_booking ?>').val()) || 0;
        //     $('#tenorInput<?= $k->id_booking ?>').val(value + 1);
        // });

        // $('#tenoskurang<?= $k->id_booking ?>').click(function() {
        //     if (value > tenor) {
        //         $('#tenorInput<?= $k->id_booking ?>').val(value - 1);
        //     } else {
        //         alert("Tenor anda sudah membayar "+tenor+" kali Pembayaran"+" silahkan sesuaikan tenor Pembayaran");
        //     }
        // });

    })
</script>

                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- /.box-body -->
</div>

