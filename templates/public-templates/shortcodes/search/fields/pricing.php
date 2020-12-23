<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$ptype = $searchform->get_pricing_type();
$max_placeholder = !empty( $data['price_range_max_placeholder'] ) ? $data['price_range_max_placeholder'] : '';
$min_placeholder = !empty( $data['price_range_min_placeholder'] ) ? $data['price_range_min_placeholder'] : '';
if ( !empty($data['label']) ): ?>
	<label><?php echo esc_html( $data['label'] ); ?></label>
<?php endif; ?>

<div class="price_ranges">

	<?php if ( $ptype == 'both' || $ptype == 'price_unit' ) {?>
		<div class="range_single"><input type="text" name="price[0]" class="form-control" placeholder="<?php echo esc_attr( $min_placeholder ); ?>" value="<?php echo esc_attr( $searchform->price_value('min') ); ?>"></div>

		<div class="range_single"><input type="text" name="price[1]" class="form-control" placeholder="<?php echo esc_attr( $max_placeholder ); ?>" value="<?php echo esc_attr( $searchform->price_value('max') ); ?>"></div>
		<?php
	}

	if ( $ptype == 'both' || $ptype == 'price_range' ) { ?>
		<div class="price-frequency">
			<label class="pf-btn">
				<?php $searchform->the_price_range_input('bellow_economy');?><span><?php echo str_repeat($searchform->c_symbol, 1); ?></span>
			</label>
			<label class="pf-btn">
				<?php $searchform->the_price_range_input('economy');?><span><?php echo str_repeat($searchform->c_symbol, 2); ?></span>
			</label>
			<label class="pf-btn">
				<?php $searchform->the_price_range_input('moderate');?><span><?php echo str_repeat($searchform->c_symbol, 3); ?></span>
			</label>
			<label class="pf-btn">
				<?php $searchform->the_price_range_input('skimming');?><span><?php echo str_repeat($searchform->c_symbol, 4); ?></span>
			</label>
		</div>
		<?php
	}
	?>

</div>