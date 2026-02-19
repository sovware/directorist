<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$phone_args = [
    'number'    => $value,
    'whatsapp'  => $listings->has_whatsapp( $data ),
];
?>

<<?php echo tag_escape( ! empty( $before ) ? $before : 'li' ); ?> class="directorist-listing-card-phone">
    <?php directorist_icon( $icon ); ?>
    <?php $listings->print_label( $label ); ?>
<a href="<?php echo esc_url( Helper::phone_link( $phone_args ) ); ?>"><?php echo esc_html( $value ); ?></a>
</<?php echo tag_escape( ! empty( $after ) ? $after : 'li' ); ?>>