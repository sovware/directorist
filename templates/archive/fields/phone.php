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
$phone_label = ! empty( $data['original_field']['label'] )
    ? $data['original_field']['label']
    : ( ! empty( $data['label'] ) ? $data['label'] : $label );
?>

<?php if ( $is_action ) : ?>
<div class="directorist-listing-card-phone">
    <a class="directorist-btn directorist-btn-xs directorist-btn-default" href="<?php echo esc_url( $phone_link ); ?>">
        <span class="directorist-icon-arrow-right">
          <?php directorist_icon( $icon ); ?>
        </span>
        <span class="directorist-btn-text">
            <?php echo esc_html( $phone_label ); ?>
        </span>
    </a>
</div>
<?php else : ?>
<li class="directorist-listing-card-phone">
    <?php directorist_icon( $icon ); ?>
    <a href="<?php echo esc_url( $phone_link ); ?>">
        <?php echo esc_html( $value ); ?>
    </a>
</li>
<?php endif; ?>
