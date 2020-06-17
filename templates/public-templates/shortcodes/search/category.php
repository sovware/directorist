<div class="single_search_field search_category">
	<select name="in_cat" id="cat-type" class="form-control directory_field bdas-category-search"<?php echo esc_attr($searchform->cat_required_text); ?>>
		<option><?php echo $searchform->category_placeholder; ?></option>
		<?php echo $searchform->categories_fields; ?>
	</select>
</div>