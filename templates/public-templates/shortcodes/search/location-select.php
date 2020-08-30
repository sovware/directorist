<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="single_search_field search_location">
	<select name="in_loc" id="<?php echo esc_attr($searchform->location_id); ?>" class="<?php echo esc_attr($searchform->location_class); ?>"<?php echo esc_attr($searchform->loc_required_text); ?>>
		<option value=""><?php echo $searchform->location_placeholder; ?></option>
		<?php echo $searchform->locations_fields; ?>
	</select>
</div>