<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$ptype = $searchform->get_pricing_type();
$max_placeholder = !empty( $data['price_range_max_placeholder'] ) ? $data['price_range_max_placeholder'] : '';
$min_placeholder = !empty( $data['price_range_min_placeholder'] ) ? $data['price_range_min_placeholder'] : '';
?>

<div class="directorist-search-field directorist-search-field-price_range directorist-price-ranges">
	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<?php if ( $ptype == 'both' || $ptype == 'price_unit' ): ?>
		<div class="directorist-custom-range-slider">
			<div class="directorist-custom-range-slider__slide"></div>
			<div class="directorist-custom-range-slider__wrap">
				<div class="directorist-custom-range-slider__value">
					<?php if ( !empty($min_placeholder) ): ?>
						<label for="directorist-custom-range-slider__value__min__pricing" class="directorist-custom-range-slider__label">
							<?php echo esc_attr( $min_placeholder ); ?>
						</label>
					<?php endif; ?>
					<span class="directorist-custom-range-slider__prefix">$</span>
					<input type="number" placeholder="Min" value="<?php echo esc_attr( $searchform->price_value('min') ); ?>" name="directorist-custom-range-slider__value__min" id="directorist-custom-range-slider__value__min__pricing" class="directorist-custom-range-slider__pricing directorist-custom-range-slider__value__min">
				</div>
				<div class="directorist-custom-range-slider__value">
					<?php if ( !empty($max_placeholder) ): ?>
						<label for="directorist-custom-range-slider__value__max__pricing" class="directorist-custom-range-slider__label">
							<?php echo esc_attr( $max_placeholder ); ?>
						</label>
					<?php endif; ?>
					<span class="directorist-custom-range-slider__prefix">$</span>
					<input type="number" placeholder="Max" value="<?php echo esc_attr( $searchform->price_value('max') ); ?>" name="directorist-custom-range-slider__value__max" id="directorist-custom-range-slider__value__max__pricing" class="directorist-custom-range-slider__pricing directorist-custom-range-slider__value__max">
				</div>
				<input type="hidden" name="directorist-custom-range-slider__range" class="directorist-custom-range-slider__range" value="<?php echo esc_attr( $searchform->price_value('max') ); ?> - <?php echo esc_attr( $searchform->price_value('max') ); ?>">
			</div>
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