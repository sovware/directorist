<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.5.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $listing->get_tags() ) ) {
	return;
}
?>

<ul class="directorist-single-tag-list">

	<?php foreach ( $listing->get_tags() as $tag ): ?>

		<li>
			<a href="<?php echo esc_url( get_term_link( $tag->term_id, ATBDP_TAGS ) ); ?>"><?php directorist_icon( $icon ); ?> <?php echo esc_html( $tag->name ); ?></a>
		</li>

	<?php endforeach; ?>

</ul>