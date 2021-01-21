<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Directorist
 */
?>
<!-- start col-md-4  -->
<div class="directorist col-lg-4 col-md-12">
    <div class="directorist atbd_sidebar">
        <!-- start search -->
        <?php dynamic_sidebar(apply_filters('atbdp_single_listing_register_sidebar', 'right-sidebar-listing')); ?>
    </div>
</div>
<!-- end col-md-4  -->