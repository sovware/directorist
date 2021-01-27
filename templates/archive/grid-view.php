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

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-archive-grid-view">

    <div class="container-fluid">

        <div class="row"<?php echo esc_attr($listings->masonary_grid_attr()); ?>>
        	<?php $listings->setup_loop( ['template' => 'grid'] ); ?>
        </div>

        <?php
        if ( $listings->show_pagination ) {
            echo atbdp_pagination( $listings->query_results );
        }
        ?>
    </div>
    
</div>