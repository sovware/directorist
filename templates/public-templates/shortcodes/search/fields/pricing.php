<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$min_price_label = __('Min Price', 'directorist');
$max_price_label = __('Max Price', 'directorist');
$has_price_field       = $searchform->has_price_field;
$has_price_range_field = $searchform->has_price_range_field;

if ( !empty($data['label']) ): ?>
	<label><?php echo esc_html( $data['label'] ); ?></label>
<?php endif; ?>

<div class="price_ranges">
	<?php if ( $has_price_field ) {?>

		<div class="range_single"><input type="text" name="price[0]" class="form-control" placeholder="<?php esc_attr( $min_price_label ); ?>" value="<?php echo esc_attr( $searchform->price_value('min') ); ?>"></div>

		<div class="range_single"><input type="text" name="price[1]" class="form-control" placeholder="<?php esc_attr( $max_price_label ); ?>" value="<?php echo esc_attr( $searchform->price_value('max') ); ?>"></div>
		<?php
	}

	if ( $has_price_range_field ) { ?>
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