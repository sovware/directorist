<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.1
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-items directorist-archive-list-view <?php echo esc_attr( $listings->pagination_infinite_scroll_class() ) ?>">
    <div class="<?php Helper::directorist_container_fluid(); ?>">

        <?php do_action( 'directorist_before_list_listings_loop' ); ?>

            <?php if ( $listings->have_posts() ) : ?>
            <?php 
             ob_start();
            Helper::directorist_row();
            $row_class = trim( ob_get_clean() );
            ?>
                <div class="<?php echo esc_attr(
                    apply_filters(
                        'directorist_list_view_wrapper_class',
                        $row_class,
                        $listings
                    )
                ); ?>">
                 <?php $listings->render_list_view( $listings->post_ids() ) ?>
                </div>

            <?php else : ?>

            <div class="directorist-archive-notfound"><?php esc_html_e( 'No listings found.', 'directorist' ); ?></div>

        <?php endif; ?>
    </div>
</div>