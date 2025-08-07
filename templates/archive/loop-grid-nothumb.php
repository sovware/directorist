<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$loop_fields = $listings->loop['card_fields']['template_data']['grid_view_without_thumbnail'];


// Capture output for quick actions
ob_start();
$listings->render_loop_fields( $loop_fields['top']['quick_actions'] );
$quick_actions_field = ob_get_clean();

// Capture output for quick_info
ob_start();
$listings->render_loop_fields( $loop_fields['top']['quick_info'] );
$quick_info_field = ob_get_clean();

// Capture output for avatar
ob_start();
$listings->render_loop_fields( $loop_fields['body']['avatar'] );
$listing_avatar = ob_get_clean();

// Capture output for title
ob_start();
$listings->render_loop_fields( $loop_fields['body']['title'] );
$listing_title = ob_get_clean();

// Capture output for tagline
ob_start();
$listings->render_loop_fields( $loop_fields['body']['tagline'] );
$listing_tagline = ob_get_clean();

// Capture output for badges
ob_start();
$listings->render_loop_fields( $loop_fields['body']['badges'] );
$listing_badges = ob_get_clean();

?>

<article class="directorist-listing-single directorist-listing-single--bg directorist-listing-card directorist-listing-no-thumb <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">
    <section class="directorist-listing-single__top">
        <?php if ( ! empty( $quick_actions_field ) ) : ?>
            <div class="directorist-listing-single__top__left">
                <?php echo $quick_actions_field; ?>
            </div>
        <?php endif; ?>
        
        <?php if ( ! empty( $quick_info_field ) ) : ?>
            <div class="directorist-listing-single__top__right">
                <?php echo $quick_info_field; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="directorist-listing-single__header">
        <?php if ( ! empty( $listing_avatar ) ) : ?>
            <figure class="directorist-listing-single__header__left">
                <?php echo $listing_avatar; ?>
            </figure>
        <?php endif; ?>
        
        <?php if ( ! empty( $listing_title ) ) : ?>
            <header class="directorist-listing-single__header__title">
                <?php echo $listing_title; ?>
            </header>
        <?php endif; ?>

        <?php if ( ! empty( $listing_tagline ) ) : ?>
            <div class="directorist-listing-single__header__tagline">
                <?php echo $listing_tagline; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="directorist-listing-single__content">
        <?php if ( ! empty( $listing_badges ) ) : ?>
            <div class="directorist-listing-single__content__badges">
                <?php echo $listing_badges; ?>
            </div>
        <?php endif; ?>
        <ul class="directorist-listing-single__info__list"><?php $listings->render_loop_fields( $loop_fields['body']['bottom'], '', '' ); ?></ul>
    </section>

    <footer class="directorist-listing-single__meta">
        <div class="directorist-listing-single__meta__left"><?php $listings->render_loop_fields( $loop_fields['footer']['left'] ); ?></div>
        <div class="directorist-listing-single__meta__right"><?php $listings->render_loop_fields( $loop_fields['footer']['right'] ); ?></div>
    </footer>

</article>