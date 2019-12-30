<?php
/*
 * Template Name: Directorist Single Listing Template
 */
get_header(); ?>

    <div id="atbdp">
        <div id="atbdp_main">

            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile; // end of the loop. ?>

        </div><!-- #content -->
    </div><!-- #primary -->

<?php
get_footer();
