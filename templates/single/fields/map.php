<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-map" data-map="<?php echo directorist_esc_json( $listing->map_data() ); ?>"></div>