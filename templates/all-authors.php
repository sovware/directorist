<?php
/**
 * @author  wpWax
 * @since   7.0.5
 * @version 7.0.5
 */

use \Directorist\Helper;
use \Directorist\Directorist_All_Authors as Author;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-w-100 directorist-authors-section" id="directorist-all-authors">

	<div class="directorist-container">

		<div class="directorist-authors">

			<?php if( $authors->display_sorting() ): ?>

				<div class="directorist-authors__nav">
					<ul>
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
					$no_author_founds = true;
					foreach( $authors->author_list() as $author ):

						if( empty( $_REQUEST['alphabet'] ) || ( ! empty( $_REQUEST['alphabet'] ) && ucfirst( $author->data->display_name )[0] == $_REQUEST['alphabet'] ) ):
							$no_author_founds = false;
							?>

							<div class="<?php Helper::directorist_column( $authors->get_columns() ); ?>">

								<div class="directorist-authors__card">

									<?php if( Author::author_profile_pic( $author->data->ID, 'pro_pic' ) && ! empty( $args['all_authors_image'] ) ): ?>
										<div class="directorist-authors__card__img">
											<img src="<?php echo Author::author_profile_pic( $author->data->ID, 'pro_pic' ); ?>" alt="<?php echo $author->data->display_name; ?>">
										</div>
									<?php endif; ?>

									<div class="directorist-authors__card__details">

										<?php if( ! empty( $author->data->display_name ) && ! empty( $args['all_authors_name'] ) ): ?>
											<h2><?php echo ucfirst( $author->data->display_name ); ?></h2>
										<?php endif; ?>

										<?php if( $author->roles[0] && ! empty( $args['all_authors_role'] ) ): ?>
											<h3><?php echo $author->roles[0]; ?></h3>
										<?php endif; ?>

										<?php if( ! empty( $args['all_authors_info'] ) ): ?>
											<ul class="directorist-authors__card__info-list">

												<?php if( Author::author_meta( $author->data->ID, 'atbdp_phone' ) ): ?>
													<li><i class="la la-phone"></i> <?php echo Author::author_meta( $author->data->ID, 'atbdp_phone' ); ?></li>
												<?php endif; ?>

												<?php if( ! empty( $author->data->user_email ) ): ?>
													<li><i class="la la-envelope"></i> <a href="mailto:<?php echo $author->data->user_email; ?>"><?php echo $author->data->user_email; ?></a></li>
												<?php endif; ?>

												<?php if( Author::author_meta( $author->data->ID, 'address' ) ): ?>
													<li><i class="la la-map-marker"></i> <?php echo Author::author_meta( $author->data->ID, 'address' ); ?></li>
												<?php endif; ?>

												<?php if( $author->data->user_url ): ?>
													<li><i class="la la-globe"></i> <a href="<?php echo esc_url( $author->data->user_url ); ?>"><?php echo esc_url( $author->data->user_url ); ?></a></li>
												<?php endif; ?>

											</ul>
										<?php endif; ?>

										<?php if( Author::author_meta( $author->data->ID, 'description' ) && ! empty( $args['all_authors_description'] ) ): ?>
											<p><?php echo wp_trim_words( Author::author_meta( $author->data->ID, 'description' ), $args['all_authors_description_limit']); ?></p>
										<?php endif; ?>

										<?php if( ! empty( $all_authors_social_info ) ): ?>
											<ul class="directorist-author-social directorist-author-social--light">

												<?php if( Author::author_meta( $author->data->ID, 'atbdp_facebook' ) ): ?>
													<li class="directorist-author-social-item">
														<a target="_blank" href="<?php echo Author::author_meta( $author->data->ID, 'atbdp_facebook' ); ?>"><span class="la la-facebook"></span></a>
													</li>
												<?php endif; ?>

												<?php if( Author::author_meta( $author->data->ID, 'atbdp_twitter' ) ): ?>
													<li class="directorist-author-social-item"><a target="_blank" href="<?php echo Author::author_meta( $author->data->ID, 'atbdp_twitter' ); ?>">
														<span class="la la-twitter"></span></a>
													</li>
												<?php endif; ?>

												<?php if( Author::author_meta( $author->data->ID, 'atbdp_linkedin' ) ): ?>
													<li class="directorist-author-social-item"><a target="_blank" href="<?php echo Author::author_meta( $author->data->ID, 'atbdp_linkedin' ); ?>">
														<span class="la la-linkedin"></span></a>
													</li>
												<?php endif; ?>

												<?php if( Author::author_meta( $author->data->ID, 'atbdp_linkedin' ) ): ?>
													<li class="directorist-author-social-item">
														<a target="_blank" href="<?php echo Author::author_meta( $author->data->ID, 'atbdp_youtube' ); ?>"><span class="la la-youtube"></span></a>
													</li>
												<?php endif; ?>

											</ul>
										<?php endif; ?>

										<?php if( ! empty( $args['all_authors_button'] ) ): ?>
											<a href="<?php echo ATBDP_Permalink::get_user_profile_page_link( $author->data->ID );?>" class="directorist-btn directorist-btn-light directorist-btn-block"><?php echo ! empty( $args['all_authors_button_text'] ) ? $args['all_authors_button_text'] : ''; ?></a>
										<?php endif; ?>

									</div>

								</div>
							</div>

						<?php endif; ?>

					<?php endforeach; ?>

					<?php if( ! empty( $no_author_founds ) ): ?>
						<p><?php _e( 'No author founds', 'directorist' ); ?></p>
					<?php endif; ?>

				</div>
			</div>

		</div>
	</div>
</div>