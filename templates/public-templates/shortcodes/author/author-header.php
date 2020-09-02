<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="row">
	<div class="col-md-12">
		<div class="atbd_auhor_profile_area">
			<div class="atbd_author_avatar">
				<?php echo $avatar_img;?>

				<div class="atbd_auth_nd">
					<h2><?php echo esc_html( $author_name ); ?></h2>
					<p><?php echo esc_html( $member_since_text ); ?></p>
				</div>
			</div>

			<div class="atbd_author_meta">

				<?php if ($enable_review) { ?>
					<div class="atbd_listing_meta">
						<span class="atbd_meta atbd_listing_rating">
							<?php echo esc_html( $rating_count ); ?><i class="<?php atbdp_icon_type(true); ?>-star"></i>
						</span>
					</div>
					<p class="meta-info"><?php echo $review_count_html;?></p>
				<?php } ?>

				<p class="meta-info"><?php echo $listing_count_html;?></p>
			</div>
		</div>
	</div>
</div>