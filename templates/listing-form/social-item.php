<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$id = (array_key_exists('id', $args)) ? $args['id'] : $index; ?>

<div class="directorist-form-social-fields" id="socialID-<?php echo $id; ?>">
    <div class="directorist-form-group">
        <select name="social[<?php echo esc_attr( $id ); ?>][id]" class="directorist-form-element">
            <?php foreach ( ATBDP()->helper->social_links() as $nameID => $socialName ) { ?>
                <option value="<?php echo esc_attr($nameID); ?>" <?php selected($nameID, $social_info['id']); ?>><?php echo esc_html($socialName); ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="directorist-form-group">
        <input type="url" name="social[<?php echo $id; ?>][url]" class="directorist-form-element directory_field atbdp_social_input" value="<?php echo esc_url($social_info['url']); ?>" placeholder="<?php esc_attr_e('eg. http://example.com', 'directorist'); ?>" required>
    </div>
    <div class="directorist-form-group directorist-form-social-fields__action">
        <span data-id="<?php echo $id; ?>" class="directorist-form-social-fields__remove directorist-btn-modal-js dashicons dashicons-trash" data-directorist_target="directorist-delete-social-js" title="<?php _e('Remove this item', 'directorist'); ?>"></span>
    </div>
</div>

<div class="directorist-modal directorist-modal-js directorist-fade directorist-delete-social-modal directorist-delete-social-js directorist-w-100">

	<div class="directorist-modal__dialog">

		<div class="directorist-modal__content">

            <div class="directorist-modal-body">

                <div class="directorist-delete-modal-inner">

                    <div class="directorist-delete-modal-icon">

                        <i class="la la-exclamation"></i>

                    </div>

                    <div class="directorist-delete-modal-text">

                        <h3>Are You Sure</h3>
                        
                        <p>Do you really want to remove this Social Link!</p>

                    </div>

                </div>
                
            </div>

            <div class="directorist-modal__footer directorist-text-center">

                <button class="directorist-btn directorist-btn-danger directorist-modal-close directorist-modal-close-js">Cancel</button>

                <button class="directorist-btn directorist-btn-info directorist-delete-social-yes directorist-modal-close-js">Yes, Delete It!</button>

            </div>

		</div>

	</div>

</div>
