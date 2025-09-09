<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.4.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_with_thumbnail'];
?>

<article class="directorist-listing-single directorist-listing-single--bg directorist-listing-card directorist-listing-has-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

    <div class="directorist-listing-single__thumb">

        <?php
        $listings->loop_thumb_card_template();
        $listings->render_loop_fields( $loop_fields['thumbnail']['avatar'] );
        ?>

        <div class="directorist-thumb-top-left"><?php $listings->render_loop_fields( $loop_fields['thumbnail']['top_left'] ); ?></div>
        <div class="directorist-thumb-top-right"><?php $listings->render_loop_fields( $loop_fields['thumbnail']['top_right'] ); ?></div>
        <div class="directorist-thumb-bottom-left"><?php $listings->render_loop_fields( $loop_fields['thumbnail']['bottom_left'] ); ?></div>
        <div class="directorist-thumb-bottom-right"><?php $listings->render_loop_fields( $loop_fields['thumbnail']['bottom_right'] ); ?></div>

    </div>

    <div class="directorist-listing-single__content">
        <section class="directorist-listing-single__info">

            <?php
            /**
             * Fires before rendering the listing header section in grid view.
             *
             * @since 8.4.6
             * @param object $listings The listings object containing loop data.
             */
            do_action( 'directorist_loop_grid_info_before_header', $listings );
            ?>

            <header class="directorist-listing-single__info__top">
                <?php $listings->render_loop_fields( $loop_fields['body']['top'], 'div', 'div' ); ?>
            </header>

            <?php
            /**
             * Fires immediately after the listing header section in grid view.
             *
             * @since 8.4.6
             * @param object $listings The listings object containing loop data.
             */
            do_action( 'directorist_loop_grid_info_after_header', $listings );
            ?>

            <ul class="directorist-listing-single__info__list">
                <?php $listings->render_loop_fields( $loop_fields['body']['bottom'], 'li', 'li' ); ?>
            </ul>

            <?php
            /**
             * Fires before displaying the listing excerpt in grid view.
             *
             * @since 8.4.6
             * @param object $listings The listings object containing loop data.
             */
            do_action( 'directorist_loop_grid_info_before_excerpt', $listings );
            ?>

            <?php if ( ! empty( $loop_fields['body']['excerpt'] ) ) : ?>
                <?php $listings->render_loop_fields( $loop_fields['body']['excerpt'] ) ?>
            <?php endif; ?>

            <?php
            /**
             * Fires after the listing excerpt is rendered in grid view.
             *
             * @since 8.4.6
             * @param object $listings The listings object containing loop data.
             */
            do_action( 'directorist_loop_grid_info_after_excerpt', $listings );
            ?>
        </section>

        <footer class="directorist-listing-single__meta">
            <div class="directorist-listing-single__meta__left"><?php $listings->render_loop_fields( $loop_fields['footer']['left'] ); ?></div>
            <div class="directorist-listing-single__meta__right"><?php $listings->render_loop_fields( $loop_fields['footer']['right'] ); ?></div>
        </footer>
    </div>

</article>