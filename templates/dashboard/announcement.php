<?php
/**
 * @author  wpWax
 * @since   7.0
 * @version 7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$skipped_post_count = 0;
?>

<div class="atbd_announcement_wrapper">
    <?php if ( $announcements->have_posts() ) : ?>
    <div class="atbdp-accordion">
        <?php while( $announcements->have_posts() ) :
            $announcements->the_post();

            // Check recepent restriction
            $recepents = get_post_meta( get_the_ID(), '_recepents', true );
            if ( ! empty( $recepents ) && is_array( $recepents )  ) {
                if ( ! in_array( $current_user_email, $recepents ) ) {
                    $skipped_post_count++;
                    continue;
                }
            }
        ?>
        <div class="atbdp-announcement <?php echo 'update-announcement-status announcement-item announcement-id-' . get_the_ID() ?>" data-post-id="<?php the_id() ?>">
            <div class="atbdp-announcement__date">
                <span class="atbdp-date-card-part-1"><?php echo get_the_date( 'd' ) ?></span>
                <span class="atbdp-date-card-part-2"><?php echo get_the_date( 'M' ) ?></span>
                <span class="atbdp-date-card-part-3"><?php echo get_the_date( 'Y' ) ?></span>
            </div>
            <div class="atbdp-announcement__content">
                <h3 class="atbdp-announcement__title">
                    <?php the_title(); ?>
                </h3>
                <p><?php the_content(); ?></p>
            </div>
            <div class="atbdp-announcement__close">
                <button class="close-announcement" data-post-id="<?php the_id() ?>">
                    <?php _e('<i class="la la-times"></i>', 'directorist'); ?>
                </button>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
        <div class="directorist_not-found"><p><?php _e( 'No announcement found', 'directorist' ) ?></p></div>
    <?php endif;

    if ( $total_posts && $skipped_post_count == $total_posts ) {
        _e( 'No announcement found', 'directorist' );
    }
    ?>
</div>