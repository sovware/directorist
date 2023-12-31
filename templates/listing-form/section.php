<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$label = $section_data['label'] ?? '';
$id    = str_replace(' ', '-', strtolower( $label ) );
?>
<section class="directorist-form-section directorist-content-module multistep-wizard__single" id="add-listing-content-<?php echo esc_attr( $id ?? '' ); ?>">
	<header class="directorist-content-module__title">
		<?php echo ! empty( $section_data['icon'] ) ?  directorist_icon( $section_data['icon'] ) : ''; ?>
		<h2><?php echo esc_html( $section_data['label'] );?></h2>
	</header>

	<section class="directorist-content-module__contents">

		<?php 
			foreach ( $section_data['fields'] as $field ) {
				$listing_form->field_template( $field );
			}
		?>

	</section>
</section>