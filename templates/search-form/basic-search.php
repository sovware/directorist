<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$fields = $searchform->get_basic_fields();
?>

<div class="directorist-search-adv-filter directorist-advanced-filter">
    <?php if ( ! empty( $fields ) ) : ?>

        <?php foreach ( $fields as $key => $field ) { ?>
            <?php $searchform->field_template( $field ); ?>
        <?php } ?>
        
    <?php endif; ?>
</div>