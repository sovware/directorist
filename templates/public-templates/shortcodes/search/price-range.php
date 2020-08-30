<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="form-group ">
	<label class=""><?php _e('Price Range', 'directorist'); ?></label>
	<div class="price_ranges">
		<?php if ( $searchform->has_price_field ) {?>

			<div class="range_single"><input type="text" name="price[0]" class="form-control" placeholder="<?php esc_attr_e('Min Price', 'directorist'); ?>" value="<?php echo esc_attr( $searchform->price_value('min') ); ?>"></div>

			<div class="range_single"><input type="text" name="price[1]" class="form-control" placeholder="<?php esc_attr_e('Max Price', 'directorist'); ?>" value="<?php echo esc_attr( $searchform->price_value('max') ); ?>"></div>
			<?php
		}

		if ( $searchform->has_price_range_field ) { ?>
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
</div>