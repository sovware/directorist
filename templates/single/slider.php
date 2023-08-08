<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !$has_slider ) {
   return;
}
$img_size_class = ( 'contain' === $data['background-size'] ) ? '' : ' plasmaSlider__cover';

$allowed_attr_for_image_item = [
	'class'    => true,
	'alt'      => true,
	'src'      => true,
	'data-src' => true,
	'data-alt' => true,
];

$allowed_html_for_image_item = [
	'img'  => $allowed_attr_for_image_item,
	'span' => $allowed_attr_for_image_item,
];

?>
<div id="directorist-single-listing-slider" class="plasmaSlider"
	data-width="<?php echo esc_attr( $data['width'] ); ?>"
	data-height="<?php echo esc_attr( $data['height'] ); ?>"
	data-rtl="<?php echo esc_attr( $data['rtl'] ); ?>"
	data-show-thumbnails="<?php echo esc_attr( $data['show-thumbnails'] ); ?>"
	data-background-size="<?php echo esc_attr( $data['background-size'] ); ?>"
	data-blur-background="<?php echo esc_attr( $data['blur-background'] ); ?>"
	data-background-color="<?php echo esc_attr( $data['background-color'] ); ?>"
	data-thumbnail-background-color="<?php echo esc_attr( $data['thumbnail-bg-color'] ); ?>">

	<div class="plasmaSliderTempImage" style="padding-top: <?php echo esc_attr( $data['padding-top'] ) ."%;" ?>">
		<?php
		if ( ! empty( $data['images'] ) ) :
			$img_src = $data['images'][0]['src'];
			$img_alt = $data['images'][0]['alt'];
			if ( 'contain' === $data['background-size'] && $data['blur-background'] ) {
				$output = "<img class='plasmaSliderTempImgBlur' src='{$img_src}' alt='{$img_alt}'>";
				echo wp_kses( $output, $allowed_html_for_image_item );
			}

			$output = "<img class='plasmaSliderTempImg {$img_size_class}' src='{$img_src}' alt='{$img_alt}'/>";
			echo wp_kses( $output, $allowed_html_for_image_item );
		endif; ?>
	</div>

	<div class="plasmaSliderImages">
		<?php
			if ( ! empty( $data['images'] )  ):
				foreach ( $data['images'] as $image ) {
					$img_src = $image['src'];
					$img_alt = $image['alt'];
					$output = "<span class='plasmaSliderImageItem' data-src='{$img_src}' data-alt='{$img_alt}'></span>" . "\n";
					echo wp_kses( $output, $allowed_html_for_image_item );
				}
			endif;
		?>
	</div>

</div>