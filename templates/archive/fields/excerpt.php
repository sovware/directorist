<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !$value ) {
	return;
}
?>

<p>
	<?php echo esc_html( wp_trim_words( $value, (int) $data['words_limit'] ) );
	if ( $data['show_readmore'] ) {
		printf( '<a href="%s"> %s</a>', $listings->loop['permalink'], $data['show_readmore_text'] );
	}
	?>
</p>