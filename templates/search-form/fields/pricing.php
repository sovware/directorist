<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$ptype           = $searchform->get_pricing_type();
$max_placeholder = !empty( $data['price_range_max_placeholder'] ) ? $data['price_range_max_placeholder'] : '';
$min_placeholder = !empty( $data['price_range_min_placeholder'] ) ? $data['price_range_min_placeholder'] : '';
$label           = ! empty( $data['label'] ) ? $data['label'] : __( 'Pricing', 'directorist' );
?>

<div class="directorist-search-field directorist-search-form-dropdown directorist-form-group <?php echo esc_attr( $empty_label ); ?>">
	<div class="directorist-search-basic-dropdown directorist-search-field__input">
		<?php if ( ! empty( $label ) ) : ?>
			<label class="directorist-search-field__label directorist-search-basic-dropdown-label">
				<?php echo esc_html( $label ); ?>
				<?php directorist_icon( 'fas fa-chevron-down' ); ?>	
			</label>
		<?php endif; ?>
		<div class="directorist-search-basic-dropdown-content">
			<div class="directorist-price-ranges">

			<?php if ( $ptype == 'both' || $ptype == 'price_unit' ): ?>

				<div class="directorist-price-ranges__item directorist-form-group">
					<?php if ( !empty($min_placeholder) ): ?>
						<label class="directorist-price-ranges__label" for="pricing-slider-range__input-values__min"><?php echo esc_attr( $min_placeholder ); ?></label>
					<?php endif; ?>
					<span class="directorist-price-ranges__currency"><?php echo $searchform->c_symbol ?? '$'; ?></span>
					<input type="number" name="price[0]" class="directorist-form-element pricing-slider-range__input-values__min" id="pricing-slider-range__input-values__min" placeholder="" value="<?php echo esc_attr( $searchform->price_value('min') ); ?>" min="0">
				</div>
				<div class="directorist-price-ranges__item directorist-form-group">
					<?php if ( !empty($max_placeholder) ): ?>
						<label class="directorist-price-ranges__label" for="pricing-slider-range__input-values__max"><?php echo esc_attr( $max_placeholder ); ?></label>
					<?php endif; ?>
					<span class="directorist-price-ranges__currency"><?php echo $searchform->c_symbol ?? '$'; ?></span>
					<input type="number" name="price[1]" class="directorist-form-element pricing-slider-range__input-values__max" id="pricing-slider-range__input-values__max" placeholder="" value="<?php echo esc_attr( $searchform->price_value('max') ); ?>" min="0">
				</div>

			<?php endif; ?>

			<?php if ( $ptype == 'both' || $ptype == 'price_range' ): ?>

				<div class="directorist-price-ranges__item directorist-price-ranges__price-frequency">
					<label class="directorist-price-ranges__price-frequency__btn">
						<?php $searchform->the_price_range_input('bellow_economy');?><span class="directorist-pf-range"><?php echo esc_html( str_repeat($searchform->c_symbol, 1) ); ?></span>
					</label>
					<label class="directorist-price-ranges__price-frequency__btn">
						<?php $searchform->the_price_range_input('economy');?><span class="directorist-pf-range"><?php echo esc_html( str_repeat($searchform->c_symbol, 2) ); ?></span>
					</label>
					<label class="directorist-price-ranges__price-frequency__btn">
						<?php $searchform->the_price_range_input('moderate');?><span class="directorist-pf-range"><?php echo esc_html( str_repeat($searchform->c_symbol, 3) ); ?></span>
					</label>
					<label class="directorist-price-ranges__price-frequency__btn">
						<?php $searchform->the_price_range_input('skimming');?><span class="directorist-pf-range"><?php echo esc_html( str_repeat($searchform->c_symbol, 4) ); ?></span>
					</label>
				</div>

			<?php endif; ?>

			</div>
		</div>
	</div>
	<div class="directorist-search-field__btn directorist-search-field__btn--clear">
		<?php directorist_icon( 'fas fa-times-circle' ); ?>	
	</div>

</div>