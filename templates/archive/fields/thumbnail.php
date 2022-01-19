<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

if ( !$listings->disable_single_listing() ) {
	echo '<a href="'.esc_url( $listings->loop_get_permalink() ).'">';
}
?>

<div class='directorist-thumnail-card directorist-card-<?php echo esc_attr( $listings->thumbnail_display_type() ); ?>' style="<?php echo esc_attr( $listings->thumbnail_style_attr() ); ?>">

	<?php if ( $listings->thumbnail_display_type() == 'contain' && $listings->display_blur_background() ): ?>

		<div class="directorist-thumnail-card-back-wrap">
			<?php echo $listings->loop_get_the_thumbnail( 'directorist-thumnail-card-back-img' );?>
		</div>

	<?php endif; ?>

	<div class="directorist-thumnail-card-front-wrap">
		<?php echo $listings->loop_get_the_thumbnail( 'directorist-thumnail-card-front-img' );?>
	</div>

</div>

<?php
if ( !$listings->disable_single_listing() ) {
	echo '</a>';
}