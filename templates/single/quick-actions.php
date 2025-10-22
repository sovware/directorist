<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="directorist-listing-single__quick-actions">
    <?php
    foreach ( $actions as $action ) :
        $listing->field_template( $action );
    endforeach;
    ?>
</div>