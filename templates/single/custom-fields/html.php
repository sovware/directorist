<?php
/**
 * @author  wpWax
 * @since   8.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-info directorist-single-info-html">

    <?php if ( ! empty( $data['label'] ) ) : ?>
    <div class="directorist-single-info__label">
        <span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
        <span class="directorist-single-info__label__text"><?php echo esc_html( $data['label'] ); ?></span>
    </div>
    <?php endif; ?>

    <div class="directorist-single-info__value">
        <?php
        $content = do_shortcode( (string) $value );
        echo wp_kses( $content, \Directorist\Fields\HTML_Field::allowed_html() );
        ?>
    </div>

</div>
