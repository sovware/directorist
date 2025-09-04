<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.4.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['list_fields']['template_data']['list_view_without_thumbnail'];
?>

<article class="directorist-listing-single directorist-listing-single--bg directorist-listing-list directorist-listing-no-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">

    <div class="directorist-listing-single__content">

        <section class="directorist-listing-single__info">

            <?php
            /**
             * Fires before rendering the listing header section in list view.
             *
             * @since 8.4.6
             * @param object $listings The listings object containing loop data.
             */
            do_action( 'directorist_loop_list_info_before_header', $listings );
            ?>

            <div class="directorist-listing-single__info__top-right">
                <header class="directorist-listing-single__info__top">
                    <?php $listings->render_loop_fields( $loop_fields['body']['top'] ); ?>
                </header>
                <div class="directorist-listing-single__info__right">
                    <div class="directorist-listing-single__action">
                        <?php $listings->render_loop_fields( $loop_fields['body']['right'] ); ?>
                    </div>
                </div>
            </div>

            <?php
            /**
             * Fires immediately after the listing header section in list view.
             *
             * @since 8.4.6
             * @param object $listings The listings object containing loop data.
             */
            do_action( 'directorist_loop_list_info_after_header', $listings );
            ?>

            <ul class="directorist-listing-single__info__list">
                <?php $listings->render_loop_fields( $loop_fields['body']['bottom'], 'li', 'li' ); ?>
            </ul>

            <?php
            /**
             * Fires before displaying the listing excerpt in list view.
             *
             * @since 8.4.6
             * @param object $listings The listings object containing loop data.
             */
            do_action( 'directorist_loop_list_info_before_excerpt', $listings );
            ?>

            <?php if ( ! empty( $loop_fields['body']['excerpt'] ) ) : ?>
                <?php $listings->render_loop_fields( $loop_fields['body']['excerpt'] ) ?>
            <?php endif; ?>

            <?php
            /**
             * Fires after the listing excerpt is rendered in list view.
             *
             * @since 8.4.6
             * @param object $listings The listings object containing loop data.
             */
            do_action( 'directorist_loop_list_info_after_excerpt', $listings );
            ?>
        </section>

        <footer class="directorist-listing-single__meta">
            <div class="directorist-listing-single__meta__left"><?php $listings->render_loop_fields( $loop_fields['footer']['left'] ); ?></div>
            <div class="directorist-listing-single__meta__right"><?php $listings->render_loop_fields( $loop_fields['footer']['right'] ); ?></div>
        </footer>

    </div>

    <footer class="directorist-listing-single__mobile-view-meta">
        <div class="directorist-listing-single__meta">
            <div class="directorist-listing-single__meta__left"><?php $listings->render_loop_fields( $loop_fields['footer']['left'] ); ?></div>
            <div class="directorist-listing-single__meta__right"><?php $listings->render_loop_fields( $loop_fields['footer']['right'] ); ?></div>
        </div>
    </footer>

</article>