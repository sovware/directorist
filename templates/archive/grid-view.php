<?php
/**
 * @template_description1
 *
 * @template_description2
 *
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-grid-view">

    <div class="<?php Helper::directorist_container(); ?>">

        <?php if ( $listings->have_posts() ): ?>
            <div class="<?php Helper::directorist_row(); ?>">
                <div class="<?php Helper::directorist_column( $listings->columns ); ?>">
                    
                </div>
                <?php $listings->setup_loop( ['template' => 'grid'] ); ?>
            </div>     
        <?php else: ?>
            
        <?php endif; ?>



        <?php
        if ( $listings->show_pagination ) {
            echo atbdp_pagination( $listings->query_results );
        }
        ?>
    </div>
    
</div>