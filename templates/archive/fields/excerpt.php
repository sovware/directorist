<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

if ( !$listings->field_value() ) {
	return;
}
?>

<p>
	<?php
	echo esc_html( wp_trim_words( $listings->field_value(), $listings->excerpt_word_limit() ) );

	if ( $listings->display_excerpt_readmore() ) {
		printf( '<a href="%s"> %s</a>', $listings->loop_get_permalink(), $listings->excerpt_readmore_text() );
	}
	?>
</p>