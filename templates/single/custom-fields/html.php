<?php
/**
 * @author  wpWax
 * @since   8.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-info directorist-single-info-html">
    <div class="directorist-single-info-html__wrapper">

        <div class="directorist-single-info-html__content">
            <?php
                $allowed_html = \Directorist\Fields\HTML_Field::allowed_html();
                $content = shortcode_unautop( wpautop( (string) $value ) );
                $content = do_shortcode( $content );
                echo wp_kses( $content, $allowed_html );
            ?>
        </div>
    </div>
</div>
