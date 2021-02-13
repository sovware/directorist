<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-card <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="directorist-card__header">
		<h4><?php directorist_icon( $icon );?><?php echo esc_html( $label );?></h4>
	</div>

	<div class="directorist-card__body">
		<?php
		foreach ( $section_data['fields'] as $field ){
			$listing->field_template( $field );
		}
		?>
	</div>
	
</div>