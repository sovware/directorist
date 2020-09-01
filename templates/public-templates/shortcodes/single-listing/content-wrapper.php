<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<section id="directorist" class="directorist atbd_wrapper">
	<div class="row">
		<div class="<?php echo esc_attr($class_col); ?> col-md-12 atbd_col_left">
			<?php if (atbdp_logged_in_user() && $author_id == get_current_user_id()) { ?>
				<div class="edit_btn_wrap">

					<?php if ( !empty($display_back_link) && !isset($_GET['redirect']) ){ ?>
						<a href="javascript:history.back()" class="atbd_go_back"><i class="<?php atbdp_icon_type(); ?>'-angle-left"></i><?php esc_html_e(' Go Back', 'directorist'); ?></a>
					<?php } ?>

					<div class="<?php echo $class_float; ?>">
						<?php
						if ($url) {
							printf('<a href="%s" class="btn btn-success">%s</a>', esc_url($url), esc_html( $submit_text ));
						}
						?>
						<a href="<?php echo esc_url($edit_link) ?>" class="btn btn-outline-light"><span class="<?php atbdp_icon_type(); ?>-edit"></span><?php echo esc_html( $edit_text ); ?></a>
					</div>

				</div>
				<?php
			}
			else { ?>
				<div class="edit_btn_wrap">
					<a href="javascript:history.back()" class="atbd_go_back"><i class="<?php atbdp_icon_type(); ?>-angle-left"></i><?php esc_html_e(' Go Back', 'directorist') ?></a>
				</div>
				<?php
			}

			echo $content;
			?>

		</div>
		
		<?php atbdp_get_shortcode_template( 'single-listing/sidebar' ); ?>
	</div>
</section>