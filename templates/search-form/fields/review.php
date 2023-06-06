<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-search-field">
	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>
	

	<div class="directorist-checkbox directorist-checkbox-rating">
		<?php foreach ( $searchform->rating_field_data() as $option ) { 
			$uniqid = $option['value'] . '_' .wp_rand();
		?>
			<input type="checkbox" name="search_by_rating_<?php echo esc_attr( $option['value'] ); ?>" value="<?php echo esc_attr( $option['value'] ); ?>" id="<?php echo esc_attr( $uniqid ); ?>" <?php echo esc_attr( $option['checked'] ); ?>>
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