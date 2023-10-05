<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$id = (array_key_exists('id', $args)) ? $args['id'] : $index; ?>

<div class="directorist-form-social-fields" id="socialID-<?php echo esc_attr( $id ); ?>">
    <div class="directorist-form-social-fields__input"> 
        <div class="directorist-form-group">
            <select name="social[<?php echo esc_attr( $id ); ?>][id]" class="directorist-form-element placeholder-item">
                <option value=""><?php esc_html_e( 'Select Network', 'directorist' ); ?></option>
                <?php foreach ( ATBDP()->helper->social_links() as $nameID => $socialName ) { ?>
                    <option value="<?php echo esc_attr($nameID); ?>" <?php selected($nameID, $social_info['id']); ?>><?php echo esc_html($socialName); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="directorist-form-group">
            <input type="url" name="social[<?php echo esc_attr( $id ); ?>][url]" class="directorist-form-element directory_field atbdp_social_input" value="<?php echo esc_url($social_info['url']); ?>" placeholder="<?php esc_attr_e('eg. http://example.com', 'directorist'); ?>" required>
        </div>
    </div>
    <div class="directorist-form-social-fields__action">
        <span data-id="<?php echo esc_attr( $id ); ?>" class="directorist-form-social-fields__remove dashicons" title="<?php esc_html_e('Remove this item', 'directorist'); ?>"><?php directorist_icon( 'fas fa-trash-alt' ); ?></span>
    </div>
</div>
