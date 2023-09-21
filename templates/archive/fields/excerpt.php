<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !$value ) {
	return;
}
?>

<div class="directorist-listing-single__info--excerpt">
	<p>
		<?php echo esc_html( wp_trim_words( $value, (int) $data['words_limit'] ) );
		if ( $data['show_readmore'] ) {
			printf( '<a href="%s"> %s</a>', esc_url( $listings->loop['permalink'] ), esc_html( $data['show_readmore_text'] ) );
		}
		?>
	</p>
</div>