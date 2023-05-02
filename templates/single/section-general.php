<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-card directorist-card-general-section <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="directorist-card__header">

		<h4 class="directorist-card__header--title">
			<span class="directorist-card__header-icon"><?php directorist_icon( $icon );?></span>
			<span class="directorist-card__header-text"><?php echo esc_html( $label );?></span>
		</h4>

	</div>

	<div class="directorist-card__body">

		<div class="directorist-details-info-wrap">
			<?php
			foreach ( $section_data['fields'] as $field ) {
				$listing->field_template( $field );
			}
			?>
		</div>

	</div>

</div>