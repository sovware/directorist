<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.8
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

$phone_args = array(
	'number'    => $listings->field_value(),
	'whatsapp'  => $listings->has_whatsapp(),
);
?>

<div class="directorist-listing-card-phone">
	<?php $listings->print_icon(); ?>
	<?php $listings->print_label(); ?>
	<a href="<?php echo esc_url( Helper::phone_link( $phone_args ) ); ?>"><?php echo esc_html( $listings->field_value() ); ?></a>
</div>