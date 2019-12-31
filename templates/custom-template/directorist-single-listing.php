<?php
/*
 * Template Name: Directorist Single Listing Template
 */
get_header(); ?>
    <style>
        #atbdp-main{
            max-width: 1080px;
            width: 100%;
            margin: 0 auto;
        }
    </style>
    <div id="atbdp-wrapper">
        <div id="atbdp-main">

            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile; // end of the loop. ?>

        </div><!-- #content -->
    </div><!-- #primary -->

<?php
get_footer();
