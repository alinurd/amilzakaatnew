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
					<li class="breadcrumb-item active"> <b>
							<?php if ($k->role === 'admin') : ?>
								<center>Management Aplikasi</center>
							<?php else : ?>
								<center><?= ucwords($k->slug) ?></center>
							<?php endif; ?>
						</b></li>
				</ol>
			</div>
			<div class="page-content ">
				<div class="container">
					<div class="panel panel-info">
						<div class="panel-heading">
							<?php if ($k->role === 'admin') : ?>
								<center>Management Aplikasi</center>
							<?php else : ?>
								<?= ucwords($k->group) ?>
								<span class="pull-right">
									<div class="row-custom">
										<p class="p-last-seen">
											<span class="last-seen <?php echo (is_user_online($k->last_seen)) ? 'last-seen-online' : ''; ?>"> <i class="icon-circle"></i> <?php echo trans("last_seen"); ?>&nbsp;<?php echo time_ago($k->last_seen); ?></span>
										</p>
									</div>

								</span>
							<?php endif; ?>
						</div>
						<div class="panel-body">
							<!-- <div class="jumbotron"> -->
								<center> <img src="<?= base_url($k->avatar) ?>" alt="Tidak Ada Logo" class="img-responsive" alt="Responsive image">
								</center>
								<p>
									<center>
										<?php if ($k->role === 'admin') : ?>
											<center>Management Aplikasi</center>
										<?php else : ?>
											<center><?= ucwords($k->kategori_nama) ?></center>
										<?php endif; ?>

									</center>
								</p>
								<center>
									<!-- total start di postingan -->
									<button type="button" class="btn btn-info "> <span class="glyphicon glyphicon-star"> </span> <?= ucwords($k->star) ?></button>
									<a class="btn btn-primary " href="#" role="button"><span class="glyphicon glyphicon-thumbs-up"></span></a>
									<?php if ($this->auth_check) : ?>
										<?php if ($this->auth_user->id != $k->id) : ?>
											<!--form follow-->
											<?php echo form_open('profile_controller/follow_unfollow_user', ['class' => 'form-inline']); ?>
											<input type="hidden" name="following_id" value="<?php echo $k->id; ?>">
											<?php if (is_user_follows($k->id, $this->auth_user->id)) : ?>
												<button class="btn  btn-custom btn-follow"><i class="icon-user-plus"></i><?php echo trans("unfollow"); ?></button>
											<?php else : ?>
												<button class="btn  btn-custom btn-follow"><i class="icon-user-plus"></i><?php echo trans("follow"); ?></button>
											<?php endif; ?>
											<?php echo form_close(); ?>
										<?php endif; ?>
									<?php else : ?>
										<a href="<?php echo lang_base_url(); ?>login" class="btn btn-md btn-custom btn-follow"><i class="icon-user-plus"></i><?php echo trans("follow"); ?></a>
									<?php endif; ?>
									<br>
									<br>
									<?php if ($k->role != 'admin') : ?>
										Tanggal Booking: <br>
										<?php foreach ($j as $ji) { ?>
											<span class="badge badge-info"><?= date($ji->tanggal) ?></span>
										<?php } ?>
									<?php endif; ?>
									<div class="row-custom">
										<p class="description">
											<?php echo html_escape($k->about_me); ?>
										</p>
									</div>
								</center>
								
							<!-- </div> -->
						</div>
					</div>
					<ul class="nav nav-pills">
						<li role="presentation" class="active"><a href="#posting" data-toggle="tab">Posting</a></li>
						<li role="presentation"><a href="#followers" data-toggle="tab"><?php echo trans("followers"); ?>&nbsp;(<?php echo count($followers); ?>)</a></li>
					</ul>
					<div class="tab-content">
						<div id="posting" class="tab-pane fade in active">
							<section id="main" class="margin-top-30">
								<div class="container">
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-8">
											<div class="content">
												<?php if ($layout == "layout_4" || $layout == "layout_5" || $layout == "layout_6") : ?>
													<div class="first-tmp-slider">
														<!--Show if enabled-->
														<?php if ($general_settings->slider_active == 1) {
															$this->load->view('partials/_slider_second', $this->slider_posts);
														} ?>
													</div>
												<?php endif; ?>
												<div class="col-xs-12 col-sm-12 posts <?php echo ($layout == "layout_3" || $layout == "layout_6") ? 'p-0 posts-boxed' : ''; ?>">
													<div class="row">
														<?php $count = 0;
														foreach ($posts as $item) :
															if ($count != 0 && $count % 2 == 0) : ?>
																<div class="col-sm-12 col-xs-12"></div>
														<?php endif;
															$this->load->view('post/_post_item', ['item' => $item]);
															if ($count == 1) :
																$this->load->view("partials/_ad_spaces", ["ad_space" => "index_top"]);
															endif;
															$count++;
														endforeach; ?>
													</div>
												</div>
												<div class="col-xs-12 col-sm-12 col-xs-12">
													<div class="row">
														<?php $this->load->view("partials/_ad_spaces", ["ad_space" => "index_bottom"]); ?>
													</div>
												</div>
												<div class="col-xs-12 col-sm-12 col-xs-12">
													<div class="row">
														<?php echo $this->pagination->create_links(); ?>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-4">
											<?php $this->load->view('partials/_sidebar'); ?>
										</div>
									</div>
								</div>
							</section>
						</div>

						<div id="followers" class="tab-pane fade">
							
						<div class="widget-followers">
										<div class="widget-head">
											<h3 class="title"><?php echo trans("followers"); ?>&nbsp;(<?php echo count($followers); ?>)</h3>
										</div>
										<div class="widget-body">
											<div class="widget-content custom-scrollbar-followers">
												<div class="row">
													<div class="col-sm-12">
														<?php if (!empty($followers)):
															foreach ($followers as $item):?>
																<div class="img-follower">
																	<a href="<?php echo lang_base_url() . "profile/" . html_escape($item->slug); ?>">
																		<img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACwAAAAAAQABAAACAkQBADs=" data-src="<?php echo get_user_avatar($item); ?>" alt="<?php echo html_escape($item->username); ?>" class="img-responsive lazyload">
																	</a>
																</div>
															<?php endforeach;
														endif; ?>
													</div>
												</div>
											</div>
										</div>z
									</div>
									<!-- <div class="social">
										<ul>
											<?php if (!empty($k->facebook_url)) : ?>
												<li><a href="<?php echo $k->facebook_url; ?>" target="_blank"><i class="icon-facebook"></i></a></li>
											<?php endif;
											if (!empty($k->twitter_url)) : ?>
												<li><a href="<?php echo $k->twitter_url; ?>" target="_blank"><i class="icon-twitter"></i></a></li>
											<?php endif;
											if (!empty($k->instagram_url)) : ?>
												<li><a href="<?php echo $k->instagram_url; ?>" target="_blank"><i class="icon-instagram"></i></a></li>
											<?php endif;
											if (!empty($k->pinterest_url)) : ?>
												<li><a href="<?php echo $k->pinterest_url; ?>" target="_blank"><i class="icon-pinterest"></i></a></li>
											<?php endif;
											if (!empty($k->linkedin_url)) : ?>
												<li><a href="<?php echo $k->linkedin_url; ?>" target="_blank"><i class="icon-linkedin"></i></a></li>
											<?php endif;
											if (!empty($k->vk_url)) : ?>
												<li><a href="<?php echo $k->vk_url; ?>" target="_blank"><i class="icon-vk"></i></a></li>
											<?php endif;
											if (!empty($k->telegram_url)) : ?>
												<li><a href="<?php echo $k->telegram_url; ?>" target="_blank"><i class="icon-telegram"></i></a></li>
											<?php endif;
											if (!empty($k->youtube_url)) : ?>
												<li><a href="<?php echo $k->youtube_url; ?>" target="_blank"><i class="icon-youtube"></i></a></li>
											<?php endif; ?>
										</ul>
									</div> -->

								</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- /.Section: main -->