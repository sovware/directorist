<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<iframe class="directorist-embaded-video embed-responsive-item" src="<?php echo esc_attr( Helper::parse_video( $value ) ); ?>" allowfullscreen></iframe>