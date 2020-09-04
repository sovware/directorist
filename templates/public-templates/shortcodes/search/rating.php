<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="form-group">
	<label><?php esc_html_e('Filter by Ratings', 'directorist'); ?></label>
	<select name='search_by_rating' class="select-basic form-control">
		<?php
		foreach ( $searchform->rating_field_data() as $option ) {
			printf('<option value="%s"%s>%s</option>', $option['value'], $option['selected'], $option['label']);
		}
		?>
	</select>
</div>