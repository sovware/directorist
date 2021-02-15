<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

$id = get_the_ID();
$type = get_post_meta( $id, '_directory_type', true );


$payment   = isset($_GET['payment']) ? $_GET['payment'] : '';
$redirect  = isset($_GET['redirect']) ? $_GET['redirect'] : '';
$edit_link = !empty($payment) ? add_query_arg('redirect', $redirect, ATBDP_Permalink::get_edit_listing_page_link($id)) : ATBDP_Permalink::get_edit_listing_page_link($id);

$display_preview = get_directorist_option('preview_enable', 1);
$submit_text = __('Continue', 'directorist');
$url = ''; 
if ($display_preview && $redirect) {
	$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : $id;
	$edited = isset($_GET['edited']) ? $_GET['edited'] : '';
	$pid = isset($_GET['p']) ? $_GET['p'] : '';
	$pid = empty($pid) ? $post_id : $pid;
	if (empty($payment)) {
		$redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');
		if( 'view_listing' === $redirect_page){
			$url = add_query_arg(array('p' => $pid, 'post_id' => $pid, 'reviewed' => 'yes', 'edited' => $edited ? 'yes' : 'no'), $redirect);
		}else{
			$url = $redirect;
		}
	} else {
		$url = add_query_arg(array('atbdp_listing_id' => $pid, 'reviewed' => 'yes'), $_GET['redirect']);
	}
}
$header 				= get_term_meta( $type, 'single_listing_header', true );

$pending_msg 			= get_directorist_option('pending_confirmation_msg', __( 'Thank you for your submission. Your listing is being reviewed and it may take up to 24 hours to complete the review.', 'directorist' ) );
$publish_msg 			= get_directorist_option('publish_confirmation_msg', __( 'Congratulations! Your listing has been approved/published. Now it is publicly available.', 'directorist' ) );
$confirmation_msg = '';
if( isset( $_GET['notice'] ) ) {
	$new_listing_status  = get_term_meta( $type, 'new_listing_status', true );
	$edit_listing_status = get_term_meta( $type, 'edit_listing_status', true );
	$edited              = ( isset( $_GET['edited'] ) ) ? $_GET['edited'] : 'no';

	if ( $edited === 'no' ) {
		$confirmation_msg = 'publish' === $new_listing_status ? $publish_msg : $pending_msg;
	} else {
		$confirmation_msg = 'publish' === $edit_listing_status ? $publish_msg : $pending_msg;
	}
}

$author_id         	  = get_post_field('post_author', $id);
$class_col         	  = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
$class_float      		  = $redirect ? 'atbdp_float_active' : 'atbdp_float_none';
$display_back_link 	  = !empty( $header['options']['general']['back']['label'] ) ? $header['options']['general']['back']['label'] : '';
$edit_text         	  = apply_filters('atbdp_listing_edit_btn_text', __(' Edit', 'directorist'));
$submit_text       	  = apply_filters('atbdp_listing_preview_btn_text', $submit_text);
$submission_confirmation = get_directorist_option('submission_confirmation', 1 );
?>
<section id="directorist" class="directorist atbd_wrapper">
	<div class="row">
	<?php
        if( isset( $_GET['notice'] ) ){ ?>
            <div class="col-lg-12">
                <div class="atbd-alert atbd-alert-info atbd-alert-dismissible">
                    <?php echo $confirmation_msg; ?>
                    <button type="button" class="atbd-alert-close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <?php } ?>
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

			\Directorist\Helper::get_template( 'single-listing/single-listing' );

			// echo $content;
			?>

		</div>

		<?php \Directorist\Helper::get_template( 'single-listing/sidebar' ); ?>
	</div>
</section>