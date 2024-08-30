<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("add_user"); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open_multipart('admin_controller/add_user_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>
                <div class="form-group">
                    <label>Nama Group</label>
                    <input type="text" name="group" class="form-control auth-form-input" placeholder="Nama Group " value="<?php echo old("group"); ?>" required>
                </div>
                <div class="form-group">
                    <label>Pimpinan</label>
                    <input type="text" name="pimpinan" class="form-control auth-form-input" placeholder="Nama pimpinan " value="<?php echo old("pimpinan"); ?>" required>
                </div>
                <div class="form-group ">
                    <label for="kategori">Kategori</label>
                    <select id="kategori" class="form-control">
                        <option selected>- pilih kategori group -</option>
                        <?php foreach($kategori as $k):?>
                        <option value="<?=$k->id?>"><?=$k->name?></option> 
                        <?php endforeach?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nomor Hp</label>
                    <input type="number" name="hp" class="form-control auth-form-input" placeholder="Noomor Hp " value="<?php echo old("hp"); ?>" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <br>
                    <textarea name="alamat" id="alamat" cols="50" rows="3"></textarea>
                </div>
                <hr>
                <center><span><i>A U T H E N T I C A T I O N</i></span></center>
                <div class="form-group">
                    <label><?php echo trans("username"); ?></label>
                    <input type="text" name="username" class="form-control auth-form-input" placeholder="<?php echo trans("username"); ?>" value="<?php echo old("username"); ?>" required>
                </div>
                <div class="form-group">
                    <label><?php echo trans("email"); ?></label>
                    <input type="email" name="email" class="form-control auth-form-input" placeholder="<?php echo trans("email"); ?>" value="<?php echo old("email"); ?>" required>
                </div>
                <div class="form-group">
                    <label><?php echo trans("password"); ?></label>
                    <input type="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("password"); ?>" value="<?php echo old("password"); ?>" required>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('role'); ?></label>
                        </div>
                        <!-- <div class="col-md-3 col-sm-4 col-xs-12 col-option">
                            <input type="radio" id="role_1" name="role" value="admin" class="square-purple" checked>
                            <label for="role_1" class="cursor-pointer"><?php echo trans('admin'); ?></label>
                        </div> -->
                        <div class="col-md-3 col-sm-4 col-xs-12 col-option">
                            <input type="radio" id="role_2" name="role" value="author" class="square-purple" checked>
                            <label for="role_2" class="cursor-pointer">Group</label>
                        </div>
                        <!-- <div class="col-md-3 col-sm-4 col-xs-12 col-option">
                            <input type="radio" id="role_3" name="role" value="user" class="square-purple">
                            <label for="role_3" class="cursor-pointer"><?php echo trans('user'); ?></label>
                        </div> -->
                    </div>
                </div>

            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_user'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>