<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$uid          = $dashboard->id;
$u_pro_pic_id = get_user_meta( $uid, 'pro_pic', true );
$u_pro_pic    = $u_pro_pic_id ? wp_get_attachment_image_src( $u_pro_pic_id, 'directory-large' ) : '';
?>

<div class="directorist-image-profile-wrap">

	<div id="user_profile_pic" class="ez-media-uploader directorist-profile-uploader" data-type="jpg, jpeg, png, gif" data-min-file-items="0" data-max-file-items="1" data-max-total-file-size="0" data-allow-multiple="0" data-show-alerts="false" data-show-file-size="false" data-featured="false" data-allow-sorting="false" data-show-info="false" data-uploader-type="avater">

		<div class="ezmu__loading-section ezmu--show">

			<span class="ezmu__loading-icon">

				<span class="ezmu__loading-icon-img-bg"></span>

			</span>

		</div>
		
		<div class="ezmu__old-files">

			<?php if ($u_pro_pic) { ?>
				<span class="ezmu__old-files-meta" data-attachment-id="<?php echo esc_attr($u_pro_pic_id); ?>" data-url="<?php echo esc_url($u_pro_pic[0]); ?>" data-type="image"></span>
			<?php } ?>

		</div>


		<div class="ezmu-dictionary">

			<span class="ezmu-dictionary-label-select-files"><?php esc_html_e('Select', 'directorist'); ?></span>

			<span class="ezmu-dictionary-label-add-more"><?php esc_html_e('Select', 'directorist'); ?></span>

			<span class="ezmu-dictionary-label-change"><?php esc_html_e('Change', 'directorist'); ?></span>

			<span class="ezmu-dictionary-alert-max-total-file-size"><?php esc_html_e('Max limit for total file size is __DT__', 'directorist'); ?></span>

			<span class="ezmu-dictionary-alert-min-file-items"><?php esc_html_e('Min __DT__ file is required', 'directorist'); ?></span>

			<span class="ezmu-dictionary-alert-max-file-items"><?php esc_html_e('Max limit for total file is __DT__', 'directorist'); ?></span>

			<span class="ezmu-dictionary-info-max-total-file-size"><?php esc_html_e('Maximum allowed file size is __DT__', 'directorist'); ?></span>

			<span class="ezmu-dictionary-info-type" data-show='0'></span>

			<span class="ezmu-dictionary-info-min-file-items"><?php esc_html_e('Minimum __DT__ file is required', 'directorist'); ?></span>

		</div>

	</div>

</div>