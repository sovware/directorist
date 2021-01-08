<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.7
 */

$id           = $listing->id;
$review_count = ATBDP()->review->db->count(array('post_id' => $id));
$author_id    = get_post_field('post_author', $id);

$author_id                = get_post_field('post_author', $id);
$enable_review            = get_directorist_option('enable_review', 1);
$enable_owner_review      = get_directorist_option('enable_owner_review');
$allow_review             = apply_filters('atbdp_single_listing_before_review_block', true);
$review_count             = $review_count;
$review_count_text        = _nx('Review', 'Reviews', $review_count, 'Number of reviews', 'directorist');
$guest_review             = get_directorist_option('guest_review', 0);
$cur_user_review          = ATBDP()->review->db->get_user_review_for_post(get_current_user_id(), $id);
$reviewer_name            = wp_get_current_user()->display_name;
$reviewer_img             = $listing->get_reviewer_img();
$guest_email_label        = get_directorist_option('guest_email', __('Your Email', 'directorist'));
$guest_email_placeholder  = get_directorist_option('guest_email_placeholder', __('example@gmail.com', 'directorist'));
$approve_immediately      = get_directorist_option('approve_immediately', 1);
$review_duplicate         = tract_duplicate_review(wp_get_current_user()->display_name, $id);
$login_link               = apply_filters('atbdp_review_login_link', "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Login', 'directorist') . "</a>");
$register_link            = apply_filters('atbdp_review_signup_link', "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign Up', 'directorist') . "</a>");

if ($enable_review && $allow_review) { ?>

	<div class="<?php echo esc_attr( $class );?>" <?php echo $id ? 'id="'.$id.'"' : '';?>>

		<div class="atbd_content_module atbd_review_module" id="atbd_reviews_block">
			<div class="atbd_content_module_title_area">
				<div class="atbd_area_title">
					<h4><span class="<?php atbdp_icon_type(true); ?>-star atbd_area_icon"></span><span id="reviewCounter"><?php echo esc_html($review_count); ?></span> <?php echo esc_html($review_count_text);?></h4>
				</div>
				<?php if (atbdp_logged_in_user() || $guest_review) { ?>
					<label for="review_content" class="btn btn-primary btn-sm"><?php esc_html_e('Add a review', 'directorist'); ?></label>
					<?php
				}
				?>
			</div>
			<div class="atbdb_content_module_contents">
				<input type="hidden" id="review_post_id" data-post-id="<?php echo esc_attr($listing->id); ?>">
				<div id="client_review_list"></div>
				<div id="clint_review"></div>
			</div>
		</div>

		<?php
	    // check if the user is logged in and the current user is not the owner of this listing.
		if (atbdp_logged_in_user() || $guest_review) {
	        // if the current user is NOT the owner of the listing print review form
	        // get the settings of the admin whether to display review form even if the user is the owner of the listing.
			if (get_current_user_id() != $author_id || $enable_owner_review) { ?>
				<div class="atbd_content_module">

					<div class="atbd_content_module_title_area">
						<div class="atbd_area_title">
							<h4><span class="<?php atbdp_icon_type(true); ?>-star" aria-hidden="true"></span><?php echo !empty($cur_user_review) ? esc_html__('Update Review', 'directorist') : esc_html__('Leave a Review', 'directorist'); ?></h4>
						</div>
					</div>

					<div class="atbdb_content_module_contents atbd_give_review_area">

						<form action="#" id="atbdp_review_form" method="post">
							<?php wp_nonce_field('atbdp_review_action_form', 'atbdp_review_nonce_form'); ?>
							<input type="hidden" name="post_id" value="<?php echo esc_attr( $listing->id ); ?>">
							<input type="hidden" name="name" class="btn btn-default" value="<?php echo esc_attr($reviewer_name); ?>" id="reviewer_name">
							<input type="hidden" name="name" id="reviewer_img" class="btn btn-default" value='<?php echo esc_attr($reviewer_img); ?>'>

							<div class="atbd_review_rating_area">
								<?php
	                            // color the stars if user has rating
								if (!empty($cur_user_review)) { ?>
									<div class="atbd_review_current_rating">
										<p class="atbd_rating_label"><?php esc_html_e('Current Rating:', 'directorist'); ?></p>
										<div class="atbd_rated_stars">
											<?php echo ATBDP()->review->print_static_rating($cur_user_review->rating); ?>
										</div>
									</div>
									<?php
								}
								?>

								<div class="atbd_review_update_rating">
									<p class="atbd_rating_label"><?php echo !empty($cur_user_review) ? esc_html__('Update Rating:', 'directorist') : esc_html__('Your Rating:', 'directorist'); ?></p>
									<div class="atbd_rating_stars">
										<select class="stars" name="rating" id="review_rating">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5" selected>5</option>
										</select>
									</div>
								</div>
							</div>

							<div class="form-group">
								<textarea name="content" id="review_content" class="form-control" cols="20" rows="5" placeholder="<?php echo !empty($cur_user_review) ? esc_html__('Update your review.....', 'directorist') : esc_html__('Write your review.....', 'directorist'); ?>"><?php echo !empty($cur_user_review) ? esc_html($cur_user_review->content) : ''; ?></textarea>
							</div>

							<?php if ($guest_review && !atbdp_logged_in_user()){ ?>
								<div class="form-group">
									<label for="guest_user"><?php echo esc_html( $guest_email_label ); ?>:<span class="atbdp_make_str_red">*</span></label>
									<input type="text" id="guest_user_email" name="guest_user_email" required class="form-control directory_field" placeholder="<?php echo esc_attr($guest_email_placeholder); ?>"/>
								</div>
								<?php
							}

							if (!empty($cur_user_review)) { ?>
								<button class="<?php echo atbdp_directorist_button_classes(); ?>" type="submit" id="atbdp_review_form_submit"><?php esc_html_e('Update', 'directorist'); ?></button>

								<button class="btn btn-danger" type="button" id="atbdp_review_remove" data-review_id="<?php echo $cur_user_review->id; ?>"><?php esc_html_e('Remove', 'directorist'); ?></button>
								<?php
							}
							else { ?>
								<button class="btn btn-primary" type="submit" id="atbdp_review_form_submit"><?php _e('Submit Review', 'directorist'); ?></button>
								<?php
							}
							?>

							<input type="hidden" name="approve_immediately" id="approve_immediately" value="<?php echo empty($approve_immediately) ? 'no' : 'yes';?>">
							<input type="hidden" name="review_duplicate" id="review_duplicate" value="<?php echo !empty($review_duplicate) ? 'yes' : '';?>">
							
						</form>
					</div>
				</div>
				<?php
			}
		}
		else { ?>
			<div class="atbd_notice alert alert-info" role="alert">
				<span class="<?php atbdp_icon_type(true); ?>-info-circle" aria-hidden="true"></span>
				<?php printf(esc_html__('You need to %s or %s to submit a review', 'directorist'), $login_link, $register_link);?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}