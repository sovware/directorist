<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.1
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-items directorist-archive-grid-view <?php echo esc_attr( $listings->pagination_infinite_scroll_class() ) ?>">
    <div class="<?php Helper::directorist_container_fluid(); ?>">

        <?php do_action( 'directorist_before_grid_listings_loop' ); ?>

        <?php if ( $listings->have_posts() ) : ?>
            <?php
            ob_start();
            Helper::directorist_row();
            $row_class = trim( ob_get_clean() );
            $default_class = ( $listings->has_masonry() ? 'directorist-masonry' : '' ) . ' ' . $row_class;
            ?>

            <div class="<?php echo esc_attr( apply_filters(
                    'directorist_grid_view_wrapper_class',
                    $default_class,
                    $listings
                ) ); ?>">
                
                <?php $listings->render_grid_view( $listings->post_ids() ) ?>

            </div>
            
            <?php
            if ( $listings->show_pagination && 'numbered' === $listings->options['pagination_type'] ) {

                do_action( 'directorist_before_listings_pagination' );

                $listings->pagination();

                do_action( 'directorist_after_listings_pagination' );
            }
            ?>

            <?php do_action( 'directorist_after_grid_listings_loop' ); ?>

        <?php else : ?>

            <div class="directorist-archive-notfound"><?php esc_html_e( 'No listings found.', 'directorist' ); ?></div>

        <?php endif; ?>
    </div>
</div>