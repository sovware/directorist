<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field directorist-search-field-review directorist-search-form-dropdown directorist-form-group <?php echo esc_attr( $empty_label ); ?>">
	<div class="directorist-search-basic-dropdown directorist-search-field__input">

		<?php if ( !empty($data['label']) ): ?>
			<label class="directorist-search-field__label directorist-search-basic-dropdown-label">
				<span class="directorist-search-basic-dropdown-selected-prefix"></span>
				<?php echo esc_html( $data['label'] ); ?>
				<span class="directorist-search-basic-dropdown-selected-count"></span>
				<?php directorist_icon( 'fas fa-chevron-down' ); ?>	
			</label>
		<?php endif; ?>
		<div class="directorist-search-basic-dropdown-content">
			<div class="directorist-search-review directorist-flex">
				<div class="directorist-checkbox directorist-checkbox-rating">
					<?php foreach ( $searchform->rating_field_data() as $option ) { 
						$uniqid = $option['value'] . '_' .wp_rand();
					?>
						<input type="checkbox" name="search_by_rating[]" value="<?php echo esc_attr( $option['value'] ); ?>" id="<?php echo esc_attr( $uniqid ); ?>" <?php echo esc_attr( $option['checked'] ); ?>>
						<label for="<?php echo esc_attr( $uniqid ); ?>" class="directorist-checkbox__label">
							<?php 
								directorist_icon( 'fas fa-star', true, 'star-empty' );
								directorist_icon( 'fas fa-star', true, 'star-empty' );
								directorist_icon( 'fas fa-star', true, 'star-empty' );
								directorist_icon( 'fas fa-star', true, 'star-empty' );
								directorist_icon( 'fas fa-star', true, 'star-empty' );
							?>
						</label>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<?php directorist_icon( 'fas fa-times-circle' ); ?>	
	</div>
</div>