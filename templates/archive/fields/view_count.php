<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>
<span class="directorist-view-count" data-id="<?php the_ID(); ?>"><?php directorist_icon( $icon ); ?><?php echo esc_html( $listings->loop['post_view'] ?? 0 ); ?></span>
