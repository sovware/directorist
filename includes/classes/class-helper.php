<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'ATBDP_Helper' ) ) :

	class ATBDP_Helper {

		private $__nonce_action = 'atbdp_nonce_action';
		private $__nonce_name   = 'atbdp_nonce';

		public function __construct() {
			add_action( 'init', array( $this, 'check_req_php_version' ), 100 );
		}

		// get_default_slider
		public static function get_default_slider( $args ) {
			$gallery_image = '';
			/*
			 $args = array(
				'image_links' => '',
				'plan_slider' => '',
				'listing_prv_img' => '',
				'display_prv_image' => '',
				'gallery_cropping' => '',
				'custom_gl_width' => '',
				'custom_gl_height' => '',
				'p_title' => '',
				'image_links_thumbnails' => '',
				'display_thumbnail_img' => '',
				); */

			if ( ! empty( $args['image_links'] ) && $args['plan_slider'] ) {
				if ( ! empty( $args['listing_prv_img'] && $args['display_prv_image'] ) ) {
					if ( ! empty( $args['gallery_cropping'] ) ) {
						$listing_prv_imgurl = atbdp_image_cropping(
							$args['listing_prv_img'],
							$args['custom_gl_width'],
							$args['custom_gl_height'],
							true,
							100
						)['url'];
					} else {
						$listing_prv_imgurl = atbdp_get_image_source( $args['listing_prv_img'], 'large' );
					}
					array_unshift( $args['image_links'], $listing_prv_imgurl );
				}
				$gallery_image .= '<div class="atbd_directry_gallery_wrapper">';
				$gallery_image .= '<div class="atbd_big_gallery">';
				$gallery_image .= '<div class="atbd_directory_gallery">';
				foreach ( $args['image_links'] as $image_link ) {
					$image_link = ! empty( $image_link ) ? $image_link : '';
					$gallery_image .= '<div class="single_image">';
					$gallery_image .= '<img src="' . esc_url( $image_link ) . '" alt=" ' . esc_html( $args['p_title'] ) . '">';
					$gallery_image .= '</div>';
				}
				$gallery_image .= '</div>';
				if ( count( $args['image_links'] ) > 1 ) {
					$gallery_image .= '<span class="prev fa fa-angle-left"></span>';
					$gallery_image .= '<span class="next fa fa-angle-right"></span>';
				}
				$gallery_image .= '</div>';
				$image_links_thumbnails = ! empty( $args['image_links_thumbnails'] ) ? $args['image_links_thumbnails'] : array();
				$listing_prv_img        = ! empty( $args['listing_prv_img'] ) ? $args['listing_prv_img'] : '';
				if ( ! empty( $args['display_thumbnail_img'] ) && ( 1 != count( $image_links_thumbnails ) || ( ! empty( $listing_prv_img ) && ! empty( $display_prv_image ) ) ) ) {
					$gallery_image .= '<div class="atbd_directory_image_thumbnail">';
					$listing_prv_imgurl_thumb = ! empty( $listing_prv_img ) ? atbdp_get_image_source( $listing_prv_img, 'thumbnail' ) : '';
					if ( ! empty( $listing_prv_imgurl_thumb && ! empty( $args['display_prv_image'] ) ) ) {
						array_unshift( $image_links_thumbnails, $listing_prv_imgurl_thumb );
					}
					foreach ( $image_links_thumbnails as $image_links_thumbnail ) {
						$gallery_image .= '<div class="single_thumbnail">';
						$gallery_image .= '<img src="' . esc_url( $image_links_thumbnail ) . '" alt="' . esc_html( $p_title ) . '">';
						$gallery_image .= '</div>';
						if ( ! is_multiple_images_active() ) {
							break;
						}
					}
					$gallery_image .= '</div>';
				}
				$gallery_image .= '</div>';
			} elseif ( ! empty( $args['display_prv_image'] ) ) {
				$default_image     = get_directorist_option( 'default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg' );
				$listing_prv_image = ! empty( $listing_prv_img ) ? esc_url( $listing_prv_imgurl ) : $default_image;
				$gallery_image .= '<div class="single_image">';
				$gallery_image .= '<img src="' . $listing_prv_image . '"
                                     alt="' . esc_html( $args['p_title'] ) . '">';
				$gallery_image .= '</div>';
			}

			return $gallery_image;
		}

		// atbdp_thumbnail_card
		public static function atbdp_thumbnail_card( $img_src = '', $_args = array() ) {
			$args = apply_filters( 'atbdp_preview_image_args', $_args );

			// Default
			$is_blur           = get_directorist_option( 'prv_background_type', 'blur' );
			$is_blur           = ( 'blur' === $is_blur ? true : false );
			$alt               = esc_html( get_the_title() );
			$container_size_by = get_directorist_option( 'prv_container_size_by', 'px' );
			$by_ratio          = ( 'px' === $container_size_by ) ? false : true;
			$image_size        = get_directorist_option( 'way_to_show_preview', 'cover' ); // contain / full / cover
			$ratio_width       = get_directorist_option( 'crop_width', 360 );
			$ratio_height      = get_directorist_option( 'crop_height', 300 );
			$blur_background   = $is_blur;
			$background_color  = get_directorist_option( 'prv_background_color', 'gainsboro' );

			$listing_img     = get_post_meta( get_the_ID(), '_listing_img', true );
			$listing_img_src = atbdp_get_image_source( $listing_img[0], 'medium' );

			$listing_prv_img = get_post_meta( get_the_ID(), '_listing_prv_img', true );
			$prv_image_src   = atbdp_get_image_source( $listing_prv_img, 'medium' );

			$default_image_src = get_directorist_option( 'default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg' );

			if ( 'cover' === $image_size ) {
				$listing_img_src   = atbdp_image_cropping( $listing_img, $ratio_width, $ratio_height, true, 100 )['url'];
				$prv_image_src     = atbdp_image_cropping( $listing_prv_img, $ratio_width, $ratio_height, true, 100 )['url'];
				$default_image_src = atbdp_image_cropping( $default_image_src, $ratio_width, $ratio_height, true, 100 )['url'];
			}

			$has_thumbnail = false;
			$thumbnail_img = '';

			if ( ! empty( $listing_img[0] ) && empty( $listing_prv_img_src ) ) {
				$thumbnail_img = $listing_img_src;
				$has_thumbnail = true;
			}
			if ( empty( $listing_img[0] ) && empty( $listing_prv_img_src ) && ! empty( $default_image_src ) ) {
				$thumbnail_img = $default_image_src;
				$has_thumbnail = true;
			}
			if ( ! empty( $listing_prv_img ) ) {
				$thumbnail_img = $prv_image_src;
				$has_thumbnail = true;
			}
			if ( ! empty( $img_src ) ) {
				$thumbnail_img = $img_src;
				$has_thumbnail = true;
			}

			if ( ! $has_thumbnail ) {
				return '';
			}
			$image = $thumbnail_img;

			// Extend Default
			if ( isset( $args['image'] ) ) {
				$image = esc_html( stripslashes( $args['image'] ) );
			}
			if ( isset( $args['image-size'] ) ) {
				$image_size = esc_html( stripslashes( $args['image-size'] ) );
			}
			if ( isset( $args['width'] ) ) {
				$ratio_width = esc_html( stripslashes( $args['width'] ) );
			}
			if ( isset( $args['height'] ) ) {
				$ratio_height = esc_html( stripslashes( $args['height'] ) );
			}
			if ( isset( $args['alt'] ) ) {
				$alt = esc_html( stripslashes( $args['alt'] ) );
			}
			if ( isset( $args['blur-background'] ) ) {
				$blur_background = esc_html( stripslashes( $args['blur-background'] ) );
			}
			if ( isset( $args['background-color'] ) ) {
				$background_color = esc_html( stripslashes( $args['background-color'] ) );
			}

			// Style
			$style = '';

			if ( $by_ratio ) {
				$padding_top_value = (int) $ratio_height / (int) $ratio_width * 100;
				$padding_top_css   = "padding-top: $padding_top_value%;";
				$style .= $padding_top_css;
			} else {
				$height_value = (int) $ratio_height;
				$height_css   = "height: {$height_value}px;";
				$style .= $height_css;
			}

			$background_color_css = '';
			if ( 'full' !== $image_size && ! $blur_background ) {
				$background_color_css = "background-color: $background_color";
				$style .= $background_color_css;
			}

			// Card Front Wrap
			$card_front_wrap = "<div class='atbd-thumbnail-card-front-wrap'>";
			$card_front__img = "<img src='$image' alt='$alt' class='atbd-thumbnail-card-front-img'/>";
			$front_wrap_html = $card_front_wrap . $card_front__img . '</div>';

			// Card Back Wrap
			$card_back_wrap = "<div class='atbd-thumbnail-card-back-wrap'>";
			$card_back__img = "<img src='$image' class='atbd-thumbnail-card-back-img'/>";
			$back_wrap_html = $card_back_wrap . $card_back__img . '</div>';

			$blur_bg = ( $blur_background ) ? $back_wrap_html : '';

			// Card Contain
			$card_contain_wrap  = "<div class='atbd-thumbnail-card card-contain' style='$style'>";
			$card_back__img     = "<img src='$image' class='atbd-thumbnail-card-back-img'/>";
			$image_contain_html = $card_contain_wrap . $blur_bg . $front_wrap_html . '</div>';

			// Card Cover
			$card_cover_wrap  = "<div class='atbd-thumbnail-card card-cover' style='$style'>";
			$card_back__img   = "<img src='$image' class='atbd-thumbnail-card-back-img'/>";
			$image_cover_html = $card_cover_wrap . $front_wrap_html . '</div>';

			// Card Full
			$card_full_wrap  = "<div class='atbd-thumbnail-card card-full' style='$background_color_css'>";
			$image_full_html = $card_full_wrap . $front_wrap_html . '</div>';

			$the_html = $image_cover_html;
			switch ( $image_size ) {
				case 'cover':
					$the_html = $image_cover_html;
					break;
				case 'contain':
					$the_html = $image_contain_html;
					break;
				case 'full':
					$the_html = $image_full_html;
					break;
			}

			echo wp_kses(
				$the_html,
				array(
					'div' => array(
						'class' => array(),
						'style' => array(),
					),
					'img' => array(
						'src' => array(),
						'alt' => array(),
						'class' => array(),
					)
				)
			);
		}

		public function check_req_php_version() {
			if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
				add_action( 'admin_notices', array( $this, 'notice' ), 100 );

				// deactivate the plugin because required php version is less.
				add_action( 'admin_init', array( $this, 'deactivate_self' ), 100 );

				return;
			}
		}

		public function notice() {
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
			?>
			<div class="error">
				<p><?php printf( esc_html__( '%1$s requires minimum PHP 5.4 to function properly. Please upgrade PHP version. The Plugin has been auto-deactivated.. You have PHP version %2$d', 'directorist' ), esc_html( ATBDP_NAME ), PHP_VERSION ); ?></p>
			</div>
			<?php
		}

		public function deactivate_self() {
			deactivate_plugins( ATBDP_BASE );
		}

		public function verify_nonce( $nonce = '', $action = '', $method = '_REQUEST' ) {
			// if we do not pass any nonce and action then use default nonce and action name on this class,
			// else check provided nonce and action
			if ( empty( $nonce ) || empty( $action ) ) {
				$nonce_name   = $this->nonce_name();
				$nonce        =  ! empty( ${$method[ $nonce_name ]} ) ? ${$method[ $nonce_name ]} : null;
				$nonce_action = $this->nonce_action();
			} else {
				$nonce        = ( ! empty( $_REQUEST[ $nonce ] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $nonce ] ) ) : null;
				$nonce_action = $action;
			}

			return wp_verify_nonce( $nonce, $nonce_action );
		}

		public function nonce_action() {
			return $this->nonce_action;
		}

		public function nonce_name() {
			return $this->nonce_name;
		}

		public function social_links() {
			$s = array(
				'facebook'       => __( 'Facebook', 'directorist' ),
				'twitter'        => __( 'Twitter', 'directorist' ),
				'linkedin'       => __( 'LinkedIn', 'directorist' ),
				'pinterest'      => __( 'Pinterest', 'directorist' ),
				'instagram'      => __( 'Instagram', 'directorist' ),
				'tumblr'         => __( 'Tumblr', 'directorist' ),
				'flickr'         => __( 'Flickr', 'directorist' ),
				'snapchat'       => __( 'Snapchat', 'directorist' ),
				'reddit'         => __( 'Reddit', 'directorist' ),
				'youtube'   => __( 'Youtube', 'directorist' ),
				'vimeo'          => __( 'Vimeo', 'directorist' ),
				'vine'           => __( 'Vine', 'directorist' ),
				'github'         => __( 'Github', 'directorist' ),
				'dribbble'       => __( 'Dribbble', 'directorist' ),
				'behance'        => __( 'Behance', 'directorist' ),
				'soundcloud'     => __( 'SoundCloud', 'directorist' ),
				'stack-overflow' => __( 'StackOverFLow', 'directorist' ),
			);
			asort( $s );

			return $s;
		}

		public static function getFreshIcon( $id ) {
			$icon = $id;
			switch ( $id ) {
				case 'youtube':
					$icon = 'youtube-play';
					break;
			}

			return $icon;
		}

		// format_date
		public static function format_date( $timestamp ) {
			$date_format = get_option( 'date_format' );
			$date        = date( $date_format, strtotime( $timestamp ) );

			return $date;
		}

		/**
		 * Darken or lighten a given hex color and return it.
		 *
		 * @param string    $hex Hex color code to be darken or lighten
		 * @param int       $percent The number of percent of darkness or brightness
		 * @param bool|true $darken Lighten the color if set to false, otherwise, darken it. Default is true.
		 *
		 * @return string
		 */
		public function adjust_brightness( $hex, $percent, $darken = true ) {
			// determine if we want to lighten or draken the color. Negative -255 means darken, positive integer means lighten
			$brightness = $darken ? -255 : 255;
			$steps      = $percent * $brightness / 100;

			// Normalize into a six character long hex string
			$hex = str_replace( '#', '', $hex );
			if ( strlen( $hex ) == 3 ) {
				$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
			}

			// Split into three parts: R, G and B
			$color_parts = str_split( $hex, 2 );
			$return      = '#';

			foreach ( $color_parts as $color ) {
				$color = hexdec( $color ); // Convert to decimal
				$color = max( 0, min( 255, $color + $steps ) ); // Adjust color
				$return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code
			}

			return $return;
		}

		/**
		 * Lists of html tags that are allowed in a content
		 *
		 * @return array List of allowed tags in a content
		 */
		public function allowed_html() {
			return array(
				'i'      => array(
					'class' => array(),
				),
				'strong' => array(
					'class' => array(),
				),
				'em'     => array(
					'class' => array(),
				),
				'a'      => array(
					'class'  => array(),
					'href'   => array(),
					'title'  => array(),
					'target' => array(),
				),

			);
		}

		/**
		 * Prints pagination for custom post
		 *
		 * @param $loop
		 * @param int  $paged
		 *
		 * @return string
		 */
		public function show_pagination( $loop, $paged = 1 ) {
			// @TODO: look into this deeply later : http://www.insertcart.com/numeric-pagination-wordpress-using-php/
			$largeNumber = 999999999; // we need a large number here
			$links       = paginate_links(
				array(
					'base'      => str_replace( $largeNumber, '%#%', esc_url( get_pagenum_link( $largeNumber ) ) ),
					'format'    => '?paged=%#%',
					'current'   => max( 1, $paged ),
					'total'     => $loop->max_num_pages,
					'prev_text' => __( '&laquo; Prev', 'directorist' ),
					'next_text' => __( 'Next &raquo;', 'directorist' ),
					'type'      => 'list',
				)
			);

			return $links;
		}

		public static function show_login_message( $message = '' ) {

			$t = ! empty( $message ) ? $message : '';
			$t = apply_filters( 'atbdp_unauthorized_access_message', $t );
			?>
			<div class="notice_wrapper">
				<div class="directorist-alert directorist-alert-warning"><?php directorist_icon( 'las la-info-circle' ); ?> <?php echo wp_kses_post( $t ); ?></div>
			</div>
			<?php
		}

		/**
		 * It converts a mysql datetime string to human readable relative time
		 *
		 * @param string $mysql_date Mysql Datetime string eg. 2018-5-11 17:02:26
		 * @param bool   $echo [optional] If $echo is true then print the value else return the value. default is true.
		 * @param string $suffix [optional] Suffix to be added to the related time. Default is ' ago.' .
		 * @return string|void It returns the relative time from a mysql datetime string
		 */
		public function mysql_to_human_time( $mysql_date, $echo = true, $suffix = ' ago.' ) {
			$date = DateTime::createFromFormat( 'Y-m-d H:i:s', $mysql_date );
			$time = human_time_diff( $date->getTimestamp() ) . $suffix;
			if ( ! $echo ) {
				return $time;
			}

			echo esc_html( $time );
		}

		/**
		 * It outputs category and location related markup for the listing
		 *
		 * @param WP_Term $cat Listing Category Object
		 * @param WP_Term $loc Listing Location Object
		 */
		public function output_listings_taxonomy_info( $cat, $loc ) {
			if ( ! empty( $cat ) || ! empty( $loc ) ) {
				?>
				<div class="general_info">
					<ul>
						<?php if ( ! empty( $cat ) ) { ?>
							<li>
								<p class="info_title"><?php esc_html_e( 'Category:', 'directorist' ); ?></p>
								<p class="directory_tag">
									<span class="fa <?php echo esc_attr( get_cat_icon( @$cat->term_id ) ); ?>" aria-hidden="true"></span>
									<span> <?php if ( is_object( $cat ) ) { ?>
											<a href="<?php echo esc_url( ATBDP_Permalink::atbdp_get_category_page( $cat ) ); ?>">
												<?php echo esc_html( $cat->name ); ?>
											</a>
										<?php } ?>
									</span>
								</p>
							</li>
							<?php
						}
						if ( ! empty( $loc ) ) {
							?>
							<li>
								<p class="info_title"><?php esc_html_e( 'Location:', 'directorist' ); ?>
									<span><?php if ( is_object( $loc ) ) { ?>
											<a href="<?php echo esc_url( ATBDP_Permalink::atbdp_get_location_page( $loc ) ); ?>">
												<?php echo esc_html( $loc->name ); ?>
											</a>
										<?php } ?>
									</span>
								</p>
							</li>
						<?php } ?>
					</ul>
				</div>
				<?php
			}
		}

		/**
		 * It prints a read more link to the given listing ID or the current post id inside a loop.
		 *
		 * @param int $id [optional] Listing ID
		 */
		public function listing_read_more_link( $id = null ) {
			if ( empty( $id ) ) {
				global $post;
				$id = $post->ID;
			}
			/*@todo; later make changeable via filter*/
			?>
			<div class="read_more_area">
				<a class="btn btn-default " href="<?php echo esc_url( get_post_permalink( $id ) ); ?>">
					<?php esc_html_e( 'Read More', 'directorist' ); ?>
				</a>
			</div>
			<?php
		}

		// sanitize_html
		public static function sanitize_html( string $subject = '', string $return_type = 'echo' ) {
			$subject = stripslashes( $subject );

			if ( 'return' === $return_type ) {
				return $subject;
			}

			echo esc_html( $subject );
		}

		// guard
		public static function guard( array $args = array() ) {
			$type           = ( ! empty( $args['type'] ) ) ? $args['type'] : 'auth';
			$login_redirect = ( ! empty( $args['login_redirect'] ) ) ? $args['login_redirect'] : false;

			if ( 'auth' === $type && ! is_user_logged_in() && ! $login_redirect ) {
				ob_start();
				// user not logged in;
				if( get_option( 'directorist_merge_dashboard_login_reg_page' ) ) {
					$error_message = sprintf( esc_html__( 'You need to be logged in to view the content of this page. You can login/sign up %s.', 'directorist' ), apply_filters( 'atbdp_listing_form_login_link', "<a href='" . esc_url( ATBDP_Permalink::get_dashboard_page_link() ) . "'> " . esc_html__( 'Here', 'directorist' ) . '</a>' ) );
				} else {
					$error_message = sprintf( esc_html__( 'You need to be logged in to view the content of this page. You can login %1$s. Don\'t have an account? %2$s', 'directorist' ), apply_filters( 'atbdp_listing_form_login_link', "<a href='" . esc_url( ATBDP_Permalink::get_login_page_link() ) . "'> " . esc_html__( 'Here', 'directorist' ) . '</a>' ), apply_filters( 'atbdp_listing_form_signup_link', "<a href='" . esc_url( ATBDP_Permalink::get_registration_page_link() ) . "'> " . esc_html__( 'Sign Up', 'directorist' ) . '</a>' ) );
				}
				?>
				<section class="directory_wrapper single_area">
					<?php self::show_login_message( $error_message ); ?>
				</section>
				<?php

				return ob_get_clean();
			}

			if ( '404' === $type ) {
				ob_start();
				?>
				<section class="directory_wrapper single_area">
					<div class="notice_wrapper">
						<div class="directorist-alert directorist-alert-warning">
							<?php directorist_icon( 'las la-info-circle' ); ?>
							<?php esc_html_e( 'Nothing to show!' ); ?>
						</div>
					</div>
				</section>
				<?php

				return ob_get_clean();
			}

			if ( 'user_type' === $type ) {
				ob_start();
				?>
				<section class="directory_wrapper single_area">
					<div class="notice_wrapper">
						<div class="directorist-alert directorist-alert-warning">
							<?php directorist_icon( 'las la-info-circle' ); ?>
							<?php esc_html_e( 'You need to be an author to add a listing.', 'directorist' ); ?>
						</div>
					</div>
				</section>
				<?php

				return ob_get_clean();
			}
			return '';
		}

		// sanitize_tel_attr
		public static function sanitize_tel_attr( string $tel = '', string $return_type = 'echo' ) {
			$tel = preg_replace( '/\D/', '', $tel );

			if ( $return_type === 'return' ) {
				return $tel;
			}

			echo esc_attr( $tel );
		}

		/**
		 * It outputs all categories and locations related markup for the listing
		 *
		 * @param array $cats [optional] the array of Listing Category Objects
		 * @param array $locs [optional] the array of Listing Location Objects
		 */
		public function output_listings_all_taxonomy_info( $cats = array(), $locs = array() ) {
			// get terms from db if not provided
			$cats = ! empty( $cats ) ? $cats : get_the_terms( null, ATBDP_CATEGORY );
			$locs = ! empty( $locs ) ? $locs : get_the_terms( null, ATBDP_LOCATION );

			if ( ! empty( $cats ) || ! empty( $locs ) ) {
				?>
				<div class="general_info">
					<ul>
						<?php if ( ! empty( $cats ) && is_array( $cats ) ) { ?>
							<li>
								<ul>
									<p class="info_title"><?php esc_html_e( 'Category:', 'directorist' ); ?></p>
									<?php foreach ( $cats as $cat ) { ?>
										<li>
											<p class="directory_tag">
												<span class="fa <?php echo esc_attr( get_cat_icon( @$cat->term_id ) ); ?>" aria-hidden="true"></span>
												<span> <?php if ( is_object( $cat ) ) { ?>
														<a href="<?php echo esc_url( ATBDP_Permalink::atbdp_get_category_page( $cat ) ); ?>">
															<?php echo esc_html( $cat->name ); ?>
														</a>
													<?php } ?>
												</span>
											</p>
										</li>
									<?php } ?>
								</ul>
							</li>
							<?php
						}

						if ( ! empty( $locs ) && is_array( $locs ) ) {
							$location_count = count( $locs );
							?>
							<li>
								<ul>
									<p class="info_title"><?php esc_html_e( 'Location:', 'directorist' ); ?></p>
									<?php
									foreach ( $locs as $loc ) {
										$location_count--; // reduce count to display comma for the right item
										?>
										<li>
											<span><?php if ( is_object( $loc ) ) { ?>
													<a href="<?php echo esc_url( ATBDP_Permalink::atbdp_get_location_page( $loc ) ); ?>">
														<?php echo esc_html( $loc->name ); ?>
													</a>
												<?php } ?>
											</span>
											<?php
													// @todo; discuss with front-end dev if it is good to put comma here directly or he will do?
											if ( $location_count >= 1 ) {
												echo ',';
											}

											?>
										</li>
									<?php } ?>
								</ul>
							</li>
						<?php } ?>
					</ul>
				</div>
				<?php
			}
		}
	}
endif;
