<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $value ) && empty( get_the_excerpt() ) ) {
	return;
}

$value = $value ? $value : get_the_excerpt();
?>

<p>
	<?php echo esc_html( wp_trim_words( $value, (int) $data['words_limit'] ) );
	if ( $data['show_readmore'] ) {
		printf( '<a href="%s"> %s</a>', esc_url( $listings->loop['permalink'] ), esc_html( $data['show_readmore_text'] ) );
	}
	?>
</p>