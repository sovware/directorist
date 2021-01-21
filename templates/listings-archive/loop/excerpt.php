<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

if ( !$value ) {
	return;
}
?>

<p class="atbd_excerpt_content">
	<?php echo esc_html( wp_trim_words( $value, (int) $data['words_limit'] ) );

	/**
	* @since 5.0.9
	*/
	do_action('atbdp_listings_after_exerpt');

	if ( $data['show_readmore'] ) { 
		printf( '<a href="%s"> %s</a>', $listings->loop['permalink'], $data['show_readmore_text'] );
	}
	?>
</p>