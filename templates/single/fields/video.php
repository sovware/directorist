<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.8
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<iframe class="directorist-embaded-video embed-responsive-item" src="<?php echo esc_url( Helper::parse_video( $value ) ); ?>" allowfullscreen></iframe>