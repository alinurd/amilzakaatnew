<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("add_user"); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open_multipart('owner/add_user_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>

                <div class="form-group">
                    <label>Nama Group</label>
                    <input type="text" name="name" class="form-control auth-form-input" placeholder="Nama group" value="<?php echo old("nama"); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control auth-form-input" placeholder="<?php echo trans("email"); ?>" value="<?php echo old("email"); ?>" required>
                </div>
                <div class="form-group ">
                    <label for="inputState">Kategori</label>
                    <select id="inputState" class="form-control">
                        <option selected>- pilih kategori owner -</option>
                        <option>Hiburan</option>
                        <option>Dekorasi</option>
                        <option>M U A</option>
                        <option>Poto grafy</option>
                    </select>
                </div>
<div class="form-group">
    <textarea name="alamat" id="alamat" cols="30" rows="10"></textarea>
</div>
                <!-- <div class="form-group">
                    <label>Logo</label>
                    <input type="file" name="username" class="form-control auth-form-input" placeholder="<?php echo trans("username"); ?>" value="<?php echo old("username"); ?>" required>
                </div> -->

            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Submit</button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>