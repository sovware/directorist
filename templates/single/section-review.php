<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !$listing->display_review() ) {
	return;
}

$review_placeholder = $listing->current_review() ? esc_html__( 'Update your review.....', 'directorist' ) : esc_html__( 'Write your review.....', 'directorist' );
$review_content = $listing->current_review() ? $listing->current_review()->content : '';
?>

<div class="atbd_content_module <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="atbd_content_module atbd_review_module" id="atbd_reviews_block">

		<div class="atbd_content_module_title_area">
			<h4><span class="<?php atbdp_icon_type( true ); ?>-star atbd_area_icon"></span><span id="reviewCounter"><?php echo esc_html( $listing->review_count() ); ?></span> <?php echo esc_html( $listing->review_count_text() );?></h4>

			<?php if ( atbdp_logged_in_user() || $listing->guest_review_enabled() ): ?>
				<label for="review_content" class="btn btn-primary btn-sm"><?php esc_html_e( 'Add a review', 'directorist' ); ?></label>
			<?php endif; ?>
		</div>

		<div class="atbdb_content_module_contents">
			<input type="hidden" id="review_post_id" data-post-id="<?php echo esc_attr($listing->id); ?>">
			<div id="client_review_list"></div>
			<div id="clint_review"></div>
		</div>

	</div>

	<?php
	if ( atbdp_logged_in_user() || $listing->guest_review_enabled() ): ?>

		<?php if (get_current_user_id() != $listing->author_id || $listing->owner_review_enabled() ): ?>

			<div class="atbd_content_module">

				<div class="atbd_content_module_title_area">
					<h4><span class="<?php atbdp_icon_type( true ); ?>-star" aria-hidden="true"></span><?php echo $listing->current_review() ? esc_html__( 'Update Review', 'directorist' ) : esc_html__( 'Leave a Review', 'directorist' ); ?></h4>
				</div>

				<div class="atbdb_content_module_contents atbd_give_review_area">
					<form action="#" id="atbdp_review_form" method="post">

						<div class="atbd_review_rating_area">

							<?php if ($listing->current_review()): ?>

								<div class="atbd_review_current_rating">

									<p class="atbd_rating_label"><?php esc_html_e('Current Rating:', 'directorist'); ?></p>

									<div class="atbd_rated_stars">
										<ul>
											<?php
											$rating = $listing->current_review()->rating;
											for ($i=1; $i<=5; $i++){
												printf( '<li><span class="%s"></span></li>', $i <= $rating ? 'rate_active' : 'rate_disable' );
											}
											?>
										</ul>
									</div>

								</div>

							<?php endif; ?>

							<div class="atbd_review_update_rating">

								<p class="atbd_rating_label"><?php echo $listing->current_review() ? esc_html__('Update Rating:', 'directorist') : esc_html__('Your Rating:', 'directorist'); ?></p>

								<div class="atbd_rating_stars">
									<select class="stars" name="rating" id="review_rating">
										<option value="1"><?php esc_html_e( '1', 'directorist' ); ?></option>
										<option value="2"><?php esc_html_e( '2', 'directorist' ); ?></option>
										<option value="3"><?php esc_html_e( '3', 'directorist' ); ?></option>
										<option value="4"><?php esc_html_e( '4', 'directorist' ); ?></option>
										<option value="5" selected><?php esc_html_e( '5', 'directorist' ); ?></option>
									</select>
								</div>

							</div>

						</div>

						<div class="form-group">

							<textarea name="content" id="review_content" class="form-control" cols="20" rows="5" placeholder="<?php echo esc_attr( $review_placeholder ); ?>"><?php echo esc_html( $review_content ); ?></textarea>
						</div>

						<?php if ( $listing->guest_review_enabled() && !atbdp_logged_in_user() ): ?>

							<div class="form-group">

								<label for="guest_user"><?php echo esc_html( $listing->guest_email_label() ); ?>:<span class="atbdp_make_str_red">*</span></label>

								<input type="text" id="guest_user_email" name="guest_user_email" required class="form-control directory_field" placeholder="<?php echo esc_attr($listing->guest_email_placeholder()); ?>"/>

							</div>

						<?php endif; ?>

						<?php if ($listing->current_review()): ?>

							<button class="btn btn-primary" type="submit" id="atbdp_review_form_submit"><?php esc_html_e( 'Update', 'directorist' ); ?></button>

							<button class="btn btn-danger" type="button" id="atbdp_review_remove" data-review_id="<?php echo $listing->current_review()->id; ?>"><?php esc_html_e( 'Remove', 'directorist' ); ?></button>
						
						<?php else: ?>

							<button class="btn btn-primary" type="submit" id="atbdp_review_form_submit"><?php esc_html_e( 'Submit Review', 'directorist' ); ?></button>

						<?php endif; ?>

						<?php wp_nonce_field('atbdp_review_action_form', 'atbdp_review_nonce_form'); ?>

						<input type="hidden" name="post_id" value="<?php echo esc_attr( $listing->id ); ?>">

						<input type="hidden" name="name" class="btn btn-default" value="<?php echo esc_attr( $listing->reviewer_name() ); ?>" id="reviewer_name">

						<input type="hidden" name="name" id="reviewer_img" class="btn btn-default" value='<?php echo esc_attr( $listing->get_reviewer_img() ); ?>'>

						<input type="hidden" name="approve_immediately" id="approve_immediately" value="<?php echo $listing->review_approve_immediately() ? 'yes' : 'no';?>">

						<input type="hidden" name="review_duplicate" id="review_duplicate" value="<?php echo $listing->review_is_duplicate() ? 'yes' : '';?>">

					</form>
				</div>

			</div>

		<?php endif; ?>

	<?php else: ?>

		<div class="atbd_notice atbd-alert atbd-alert-info">
			<span class="<?php atbdp_icon_type( true ); ?>-info-circle" aria-hidden="true"></span>

			<?php printf(__('You need to <a href="%s">%s</a> or <a href="%s">%s</a> to submit a review', 'directorist'), ATBDP_Permalink::get_login_page_link(), esc_html__( 'Login', 'directorist' ), ATBDP_Permalink::get_registration_page_link(), esc_html__(' Sign Up', 'directorist' ) );?>
		</div>

	<?php endif; ?>

</div>