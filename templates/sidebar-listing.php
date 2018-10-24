<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Directorist
 */


 if (  is_active_sidebar( 'right-sidebar-listing' ) ) { ?>
<!-- start col-md-4  -->
<div class="directorist col-md-4">
    <div class="directorist sidebar_m">
        <!-- start search -->
        <?php dynamic_sidebar('right-sidebar-listing'); ?>
    </div>
</div>
<!-- end col-md-4  -->
<?php } ?>