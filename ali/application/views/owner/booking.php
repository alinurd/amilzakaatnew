<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Section: main -->
<section id="main">
	<div class="container">
		<div class="row">
			<!-- breadcrumb -->
			<div class="page-breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">
						<a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a>
					</li>
					<li class="breadcrumb-item">
						<a href="<?php echo base_url('owner'); ?>">Owner</a>
					</li>
					<li class="breadcrumb-item">
						<a href="#">Detail</a>
					</li>
					<li class="breadcrumb-item active"> <b>Owner Name</b></li>
				</ol>
			</div>
			<div class="page-content ">
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= $k->title ?>
							<span class="pull-right"><?= $k->group ?></span>
						</div>
						<div class="panel-body">
							<b>Form Booking</b>
							<!-- <button class="btn btn-secondary pull-right" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Lihat Product dan Owner</button><br><br> -->
							<div class="jumbotron">
								<div class="panel panel-primary">
									<div class="panel-body">
										<div class="card card-body">
											<div class="jumbotron">
												<center>
													<?php $this->load->view("post/_post_image_booking", ['post_item' => $k, 'type' => 'image_small']); ?></center>
											</div>
										</div>
									</div>
								</div>
								<?php if ($this->auth_check) { ?>

									<?php echo form_open_multipart('owner_controller/add_booking'); ?>
									<?php $this->load->view('admin/includes/_messages'); ?>
									<input type="hidden" name="group_id" value="<?php echo $k->user_id; ?>">
									<input type="hidden" name="post_id" value="<?php echo $k->id_post; ?>">
									<div class="list-group">
										<div class="form-group">
											<label class="control-label">Email :</label>
											<input type="email" class="form-control" name="email" value="<?= $this->auth_user->email ?>" readonly required>
										</div>

										<div class="form-group">
											<label class="control-label">Nama Lengkap:</label>
											<input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required>
										</div>
										<div class="form-group">
											<label class="control-label">Whatsapp :</label>
											<input type="number" class="form-control" name="hp" placeholder="Nomor Whatsapp" required>
										</div>
										<div class="form-group">
											<label class="control-label">Tanggal Acara :</label>
											<input type="date" class="form-control" name="tgl" required>
										</div>
										<div class="form-group">
											<label class="control-label">Tenor Pembayaran :</label>
											<div class="input-group">
												<span class="input-group-btn">
													<button class="btn btn-primary btn-md" id="tenoskurang" type="button">-</button>
												</span>
												<input id="tenorInput" type="text" readonly class="form-control text-center col-md-6" name="tenor" placeholder="Tenor Pembayaran" value="1">
												<span class="input-group-btn">
													<button class="btn btn-primary btn-md" id="tenortambah" type="button">+</button>
												</span>
											</div>
										</div>


										<div class="form-group">
											<label class="control-label">Alamat Lengkap:</label>
											<textarea class="form-control text-area" name="alamat" placeholder="Contoh:  Kampung Baru RT 001 RW 002, Desa Mulya, Kecamatan Sejahtera, Kabupaten Maju Jaya, Provinsi Sejahtera " <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?> required><?php echo old('alamat'); ?></textarea>
										</div>

										<div class="">
											<center>
												<h6> Harga Rp.
													<?php echo html_escape($k->harga !== null && $k->harga !== 0 ? number_format($k->harga) : '0'); ?>
												</h6>
												<h6>Biaya penanganan: Rp. 120.000</h6>
												<h6>Total Bayar: <b>Rp. <span id="ttlBayar">
															<?php
															$harga = $k->harga + 120000;
															echo html_escape($k->harga !== null && $k->harga !== 0 ? number_format($harga) : '0'); ?>
														</span></b></h6>
											</center>
										</div><br>
										<div>
											<center>
												<button class=" btn btn-primary">Booking Sekarang</button><br><br>
												<a style="color:blue" target="_blank" href="https://wa.me/6285717570339?text=Halo%20Admin,%20saya%20tertarik%20 <?= $k->nama_kategori ?>%20dari%20grup%20<?= $k->group ?>%20(<?= $k->title ?>)%20apakah%20boleh%20tanya-tanya%20dulu%3F">Tanya-Tanya Dulu?</a>
											</center>
										</div>
									</div>
							</div>
							<?php echo form_close(); ?>
						<?php } else { ?>
							<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
							<!DOCTYPE html>
							<html>

							<head>
								<meta charset="utf-8">
								<meta http-equiv="X-UA-Compatible" content="IE=edge">
								<title><?php echo html_escape($title); ?> - <?php echo trans("admin"); ?>&nbsp;<?php echo html_escape($this->settings->site_title); ?></title>
								<!-- Tell the browser to be responsive to screen width -->
								<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

								<link rel="shortcut icon" type="image/png" href="<?php echo get_favicon($this->general_settings); ?>" />

								<!-- Bootstrap 3.3.7 -->
								<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
								<!-- Theme style -->
								<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/AdminLTE.min.css">
								<!-- AdminLTE Skins -->
								<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/_all-skins.min.css">

								<!-- Custom css -->
								<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/custom.css">

								<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
								<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
								<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
							</head>

							<body class="hold-transition login-page">
								<div class="login-box">
								
									<div class="login-box-body">
										<center>
										<i>Untuk melakukan booking acara anda harus <?php echo trans("login"); ?> terlebih dahulu !</i> <br><br>
										</center>
										<h4 class="login-box-msg"><u>Login Disini</u></h4>
										<!-- include message block -->
										<?php $this->load->view('partials/_messages'); ?>
 										<!-- form start -->
										<?php echo form_open('common_controller/admin_login_post_booking'); ?>
										<input type="hidden" name="base_url" value="<?php echo base_url(); ?>owner/booking/<?php echo $id ?>">
										<div class="form-group has-feedback">
											<input type="text" name="username" class="form-control form-input" placeholder="<?php echo trans("username_or_email"); ?>" value="<?php echo old('username'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
											<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
										</div>

										<div class="form-group has-feedback">
											<input type="password" name="password" class="form-control form-input" placeholder="<?php echo trans("password"); ?>" value="<?php echo old('password'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
											<span class=" glyphicon glyphicon-lock form-control-feedback"></span>
										</div>

										<div class="row">
											<div class="col-sm-12 col-xs-12">
										<center>
										<button type="submit" class="btn btn-primary btn-block btn-flat">
													<?php echo trans("login"); ?>
												</button> <br>
												<a href="<?php echo base_url("register"); ?>">Tidak Punya Akun?</a>

										</center>
											</div>
											
										</div>

										<?php echo form_close(); ?><!-- form end -->

									</div><!-- /.login-box-body -->

								</div><!-- /.login-box -->
							</body>

							</html>
						<?php }; ?>


						</div>
					</div>
				</div>
			</div>
		</div>
</section>
<!-- /.Section: main -->

<script>
	$(document).ready(function() {
		$('#tenortambah').click(function() {
			var value = parseInt($('#tenorInput').val()) || 0;
			$('#tenorInput').val(value + 1);
		});

		$('#tenoskurang').click(function() {
			var value = parseInt($('#tenorInput').val()) || 0;
			if (value > 1) {
				$('#tenorInput').val(value - 1);
			} else {
				alert("Tenor Pembayaran Minimal 1 Kali")
			}
		});
	});
</script>