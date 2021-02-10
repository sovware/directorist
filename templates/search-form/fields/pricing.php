<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$ptype = $searchform->get_pricing_type();
$max_placeholder = !empty( $data['price_range_max_placeholder'] ) ? $data['price_range_max_placeholder'] : '';
$min_placeholder = !empty( $data['price_range_min_placeholder'] ) ? $data['price_range_min_placeholder'] : '';
?>

<div class="directorist-search-field"> 

	<?php if ( !empty($data['label']) ): ?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif; ?>

	<div class="directorist-price-ranges">

		<?php if ( $ptype == 'both' || $ptype == 'price_unit' ): ?>

			<div class="directorist-price-ranges__item directorist-form-group"><input type="text" name="price[0]" class="directorist-form-element" placeholder="<?php echo esc_attr( $min_placeholder ); ?>" value="<?php echo esc_attr( $searchform->price_value('min') ); ?>"></div>
			<div class="directorist-price-ranges__item directorist-form-group"><input type="text" name="price[1]" class="directorist-form-element" placeholder="<?php echo esc_attr( $max_placeholder ); ?>" value="<?php echo esc_attr( $searchform->price_value('max') ); ?>"></div>
			
		<?php endif; ?>

		<?php if ( $ptype == 'both' || $ptype == 'price_range' ): ?>

			<div class="directorist-price-ranges__item directorist-price-ranges__price-frequency">
				<label class="directorist-price-ranges__price-frequency--btn">
					<?php $searchform->the_price_range_input('bellow_economy');?><span class="directorist-pf-range"><?php echo str_repeat($searchform->c_symbol, 1); ?></span>
				</label>
				<label class="directorist-price-ranges__price-frequency--btn">
					<?php $searchform->the_price_range_input('economy');?><span class="directorist-pf-range"><?php echo str_repeat($searchform->c_symbol, 2); ?></span>
				</label>
				<label class="directorist-price-ranges__price-frequency--btn">
					<?php $searchform->the_price_range_input('moderate');?><span class="directorist-pf-range"><?php echo str_repeat($searchform->c_symbol, 3); ?></span>
				</label>
				<label class="directorist-price-ranges__price-frequency--btn">
					<?php $searchform->the_price_range_input('skimming');?><span class="directorist-pf-range"><?php echo str_repeat($searchform->c_symbol, 4); ?></span>
				</label>
			</div>

		<?php endif; ?>

	</div>

</div>