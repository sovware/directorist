<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$is_action  = ! empty( $before ) && 'div' === $before;
$is_whatsapp = $listings->has_whatsapp( $data );
$phone_link = Helper::phone_link(
    [
        'number'   => $value,
        'whatsapp' => $is_whatsapp,
    ]
);
$phone_icon = $is_whatsapp ? 'lab la-whatsapp' : $icon;
?>

<?php if ( $is_action ) : ?>
<div class="directorist-listing-card-phone">
    <a class="directorist-btn directorist-btn-xs directorist-btn-primary" href="<?php echo esc_url( $phone_link ); ?>">
        <?php directorist_icon( $phone_icon ); ?>
        <?php echo esc_html( $data['label'] ); ?>
    </a>
</div>
<?php else : ?>
<li class="directorist-listing-card-phone">
    <?php directorist_icon( $phone_icon ); ?>
    <?php $listings->print_label( $label ); ?>
    <a href="<?php echo esc_url( $phone_link ); ?>">
        <?php echo esc_html( $value ); ?>
    </a>
</li>
<?php endif; ?>
