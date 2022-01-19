<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

$is_blur           = get_directorist_option('prv_background_type', 'blur');
$is_blur           = ('blur' === $is_blur ? true : false);
$blur_background   = $is_blur;
$image_size        = get_directorist_option('way_to_show_preview', 'cover');


if (!$listings->disable_single_listing()) {
	echo '<a href="'.esc_url( $listings->loop_get_permalink() ).'">';
}
?>

<div class='directorist-thumnail-card directorist-card-<?php echo $image_size; ?>' style="<?php echo esc_attr( $listings->thumbnail_style_attr() ); ?>">

	<?php if ( $image_size == 'contain' && $is_blur ): ?>

		<div class="directorist-thumnail-card-back-wrap">
			<?php echo $listings->loop_get_the_thumbnail( 'directorist-thumnail-card-back-img' );?>
		</div>

	<?php endif; ?>

	<div class="directorist-thumnail-card-front-wrap">
		<?php echo $listings->loop_get_the_thumbnail( 'directorist-thumnail-card-front-img' );?>
	</div>

</div>

<?php
if (!$listings->disable_single_listing()) {
	echo '</a>';
}