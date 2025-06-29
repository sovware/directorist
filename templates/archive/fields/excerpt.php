<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !$value ) {
	return;
}
?>

<p class="directorist-listing-single__info__excerpt">
	<?php 
	echo esc_html( wp_trim_words( $value, (int) $data['words_limit'] ) );
	if ( $data['show_readmore'] ) {
		// Add the permalink with the 'read more' text as a link
		printf( '<a href="%s"> %s</a>', esc_url( get_permalink() ), esc_html( $data['show_readmore_text'] ) );
	}
	?>
</p>
