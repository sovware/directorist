<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<section class="directorist-card directorist-card-general-section <?php echo esc_attr( $class );?>" 
	<?php $listing->section_id( $id ); ?>>

	<header class="directorist-card__header">

		<h3 class="directorist-card__header__title">
			<?php if ( ! empty( $icon ) ) : ?>
				<span class="directorist-card__header-icon"><?php directorist_icon( $icon ); ?></span>
			<?php endif; ?>
			<span class="directorist-card__header-text"><?php echo esc_html( $label ); ?></span>
		</h3>

	</header>

	<div class="directorist-card__body">

		<div class="directorist-details-info-wrap">
			<?php
			foreach ( $section_data['fields'] as $field ) {
				$listing->field_template( $field );
			}
			?>
		</div>

	</div>

</section>