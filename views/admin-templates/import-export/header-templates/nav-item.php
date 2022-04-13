<?php defined( 'ABSPATH' ) || exit; ?>

<li class="<?php echo esc_attr( $args['nav_item_class'] ); ?>">
    <span class="step">
        <span class="step-count"><?php echo esc_html( $args['step_count'] ); ?></span> 
        <span class="dashicons dashicons-yes"></span>
    </span>
    <span class="step-text"><?php echo esc_html( $args['label'] ); ?></span>
</li>