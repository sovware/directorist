<?php
/**
 * @author  wpWax
 * @since   7.0.5
 * @version 7.4.0
 */

use \Directorist\Helper;
use \Directorist\Directorist_All_Authors as Authors;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-w-100 directorist-authors-section" id="directorist-all-authors">

	<div class="directorist-container">

		<div class="directorist-authors">

			<?php if( $authors->display_sorting() ): ?>

				<div class="directorist-authors__nav">
					<ul>
						<li>
							<a href="#" class="directorist-alphabet ALL" data-nonce="<?php echo esc_attr( wp_create_nonce( 'directorist_author_sorting' ) ); ?>" data-alphabet="ALL"><?php esc_html_e( 'All', 'directorist' ); ?></a>
						</li>
						<?php foreach( range( 'A', 'Z' ) as $value ): ?>

							<li>
								<a href="#" class="directorist-alphabet <?php echo esc_attr( $value ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'directorist_author_sorting' ) ); ?>" data-alphabet="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $value ); ?></a>
							</li>

						<?php endforeach; ?>
					</ul>
				</div>

			<?php endif; ?>

			<div class="directorist-authors__cards">

				<div class="directorist-row">

					<?php
					if( $authors->author_list() ):

						foreach( $authors->author_list() as $author ): ?>

							<div class="<?php Helper::directorist_column( $authors->get_columns() ); ?>">

								<div class="directorist-authors__card">

									<?php if( ( $image = Authors::user_image_src( $author ) ) && $authors->display_image() ): ?>

										<div class="directorist-authors__card__img">
											<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( Helper::user_info( $author, 'name' ) ); ?>">
										</div>

									<?php endif; ?>

									<div class="directorist-authors__card__details">

										<div class="directorist-authors__card__details__top">
											<?php if( $authors->display_name() ): ?>
												<h2><?php echo esc_html( Helper::user_info( $author, 'name' ) ) ; ?></h2>
											<?php endif; ?>
										</div>

										<?php if( $authors->contact_info() ) : ?>

											<ul class="directorist-authors__card__info-list">

												<?php if( in_array( 'phone', $authors->contact_info() ) && $phone = Helper::user_info( $author, 'phone' ) ): ?>
													<li><?php directorist_icon( 'las la-phone' ); ?> <a href="tel:<?php Helper::formatted_tel( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></li>
												<?php endif; ?>

												<?php if( is_user_logged_in() && in_array( 'email', $authors->contact_info() ) && $email = Helper::user_info( $author, 'email' ) ) : ?>
													<li><?php directorist_icon( 'las la-envelope' ); ?> <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
												<?php endif; ?>

												<?php if( in_array( 'address', $authors->contact_info() ) && $address = Helper::user_info( $author, 'address' ) ): ?>
													<li><?php directorist_icon( 'las la-map-marker' ); ?> <?php echo esc_html( $address ); ?></li>
												<?php endif; ?>

												<?php if( in_array( 'website', $authors->contact_info() ) && $website = Helper::user_info( $author, 'website' ) ): ?>
													<li><?php directorist_icon( 'las la-globe' ); ?> <a href="<?php echo esc_url( $website ); ?>"><?php echo esc_html( $website ); ?></a></li>
												<?php endif; ?>

											</ul>

										<?php endif; ?>

										<?php if( Helper::user_info( $author, 'description' ) && $authors->display_description() ): ?>
											<p><?php echo esc_html( wp_trim_words( Helper::user_info( $author, 'description' ), $authors->description_limit() ) ); ?></p>
										<?php endif; ?>

										<?php if( $authors->display_social_info() ): ?>

											<ul class="directorist-author-social directorist-author-social--light">

												<?php if( $facebook = Helper::user_info( $author, 'facebook' ) ): ?>
													<li class="directorist-author-social-item">
														<a target="_blank" href="<?php echo esc_url( $facebook ); ?>"><?php directorist_icon( 'lab la-facebook' ); ?></a>
													</li>
												<?php endif; ?>

												<?php if( $twitter = Helper::user_info( $author, 'twitter' ) ): ?>
													<li class="directorist-author-social-item">
														<a target="_blank" href="<?php echo esc_url( $twitter ); ?>"><?php directorist_icon( 'lab la-twitter' ); ?></a>
													</li>
												<?php endif; ?>

												<?php if( $linkedin = Helper::user_info( $author, 'linkedin' ) ): ?>
													<li class="directorist-author-social-item">
														<a target="_blank" href="<?php echo esc_url( $linkedin ); ?>"><?php directorist_icon( 'lab la-linkedin' ); ?></a>
													</li>
												<?php endif; ?>

												<?php if( $youtube = Helper::user_info( $author, 'youtube' ) ): ?>
													<li class="directorist-author-social-item">
														<a target="_blank" href="<?php echo esc_url( $youtube ); ?>"><?php directorist_icon( 'lab la-youtube' ); ?></span></a>
													</li>
												<?php endif; ?>

											</ul>

										<?php endif; ?>

										<?php if( $authors->display_btn() ): ?>
											<a href="<?php echo esc_url( ATBDP_Permalink::get_user_profile_page_link( $author->data->ID ) );?>" class="directorist-btn directorist-btn-light directorist-btn-block"><?php echo esc_html( $authors->btn_text() ); ?></a>
										<?php endif; ?>

									</div>

								</div>
							</div>

						<?php
						endforeach;

					else:
						?>

						<p><?php esc_html_e( 'No authors found', 'directorist' ); ?></p>

					<?php endif; ?>

				</div>

				<div class="directorist-pagination directorist-authors__pagination directorist-authors-pagination">
					<?php echo wp_kses_post( $authors->author_pagination() ); ?>
				</div>

			</div>

		</div>
	</div>
</div>