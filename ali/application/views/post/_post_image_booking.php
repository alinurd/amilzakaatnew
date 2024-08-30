<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($post_item)) :
	if ($type == 'image_slider') {
		$img_url = get_post_image($post_item, 'slider');
		$bg = base_url() . "assets/img/bg_slider.png";
		$icon = "post-icon-md";
	} elseif ($type == 'image_small') {
		$img_url = get_post_image($post_item, 'small');
		$bg = base_url() . "assets/img/bg_small.png";
		$icon = "post-icon-sm";
	} else {
		$img_url = get_post_image($post_item, 'mid');
		$bg = base_url() . "assets/img/bg_mid.png";
		$icon = "post-icon-md";
	}

	if (!empty($post_item->image_url) || $post_item->image_mime == 'gif' || $post_item->post_type == 'video') : ?>
		<div class="external-image-container">
			<?php if ($post_item->post_type == 'video') : ?>
				<img src="<?php echo base_url(); ?>assets/img/icon_play.svg" alt="icon" class="post-icon <?php echo $icon; ?>" />
			<?php endif; ?>
			<img src="<?php echo $bg; ?>" class="img-responsive" alt="<?php echo html_escape($post_item->title); ?>">
			<!-- <img src="<?php echo $bg; ?>" data-src="<?php echo $img_url; ?>" alt="<?php echo html_escape($post_item->title); ?>" class="lazyload img-external" onerror='<?php echo $bg; ?>'> -->
		</div>
	<?php else : ?>
		<div class="media">
			<div class="media-left">
				<a href="#">
					<img class="media-object" src="<?php echo $img_url; ?>" alt="<?php echo html_escape($post_item->title); ?>">
				</a>
			</div>
			<div class="media-body">
				<span class="media-heading">
					<b><?php echo html_escape($post_item->title); ?></b><br><span style="font-size: 12px;"><?php echo html_escape($post_item->title_slug); ?></span><br><strong>Rp.
					<?php echo html_escape($post_item->harga !== null && $post_item->harga !== 0 ? number_format($post_item->harga) : '0'); ?>
				</strong> </span>
				<br>
				<br>

				<span style="font-size: 16px;"><b><?php echo html_escape($post_item->group); ?></b> </span> <br>
				
				<span style="font-size: 12px;"><?php echo html_escape($post_item->pimpinan); ?> </span> <br>
				<span style="font-size: 10px;"><i><?php echo html_escape($post_item->alamat); ?></i> </span> <br><br>
				<center>
					 <span style="font-size: 10px;"><?php echo ($post_item->content); ?></span> <br>
				</center>
				
			</div>
		</div>

<?php endif;
endif; ?>