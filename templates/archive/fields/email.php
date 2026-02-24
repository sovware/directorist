<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$is_action = ! empty( $before ) && 'div' === $before;
?>

<?php if ( $is_action ) : ?>
<div class="directorist-listing-card-email">
    <a class="directorist-btn directorist-btn-xs directorist-btn-default" href="mailto:<?php echo esc_attr( $value ); ?>">
        <?php directorist_icon( $icon ); ?>
        <?php echo esc_html( $data['label'] ); ?>
    </a>
</div>
<?php else : ?>
<li class="directorist-listing-card-email">
    <?php directorist_icon( $icon ); ?>
    <?php $listings->print_label( $label ); ?>
    <a href="mailto:<?php echo esc_attr( $value ); ?>">
        <?php echo esc_html( $value ); ?>
    </a>
</li>
<?php endif; ?>