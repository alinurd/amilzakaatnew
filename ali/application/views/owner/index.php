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
					<li class="breadcrumb-item active">Owner</li>
				</ol>
			</div>
			<div class="page-content">
				<div class="container">
				<div class="row ">
					<?php foreach ($data as $k) { ?>
						<div class="col-xs-6 col-sm-4 col-md-3">
							<div class="panel panel-primary">
							<div class="panel-heading"><?= ucwords($k->group) ?></div>
								<div class="panel-body">
									<center>
									<!-- <img src="<?= base_url($k->avatar) ?>" alt="Tidak Ada Logo" width="150" height="130"> -->
										<br><span style="font-size: 10px;"> <b>Pimpinan: <?= strtoupper($k->pimpinan) ?></b></span><br>
										<span class="badge badge-primary"><?=$k->kategori_nama?></span>
									</center>
								</div>
								<div class="panel-footer">
									<center>
										<div class="btn-group" role="group" aria-label="...">
											<button type="button" class="btn btn-info"> <span class="glyphicon glyphicon-star"> </span> <?=$k->star?></button>
											<a href="<?php echo base_url('owner/detail/'.$k->id); ?>" class="btn btn-default" type="button">Detail</a>
	 										</div>
									</center>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- /.Section: main -->