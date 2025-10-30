<?php
/**
 * @author  wpWax
 * @since 8.5
 * @version 1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use \Directorist\ATBDP_Shortcode;
?>

<?php get_header(); ?>

<main id="site-content">
    <header class="archive-header">
        <div class="archive-header-inner">
            <?php the_archive_title( '<h1 class="archive-title">', '</h1>' ); ?>
        </div><!-- .archive-header-inner -->
    </header><!-- .archive-header -->

    <?php echo ( new ATBDP_Shortcode() )->category_archive(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</main>

<?php get_footer( 'directorist' ); ?>
