<div class="form-group ads-filter-tags">
	<label><?php echo $searchform->tag_label; ?></label>
	<div class="bads-tags">
		<?php
			$rand = rand();
			foreach ($searchform->tag_terms as $term) {
			?>
			<div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
				<input type="checkbox" class="custom-control-input" name="in_tag[]" value="<?php echo $term->term_id; ?>" id="<?php echo $rand . $term->term_id; ?>" <?php if (!empty($_GET['in_tag']) && in_array($term->term_id, $_GET['in_tag'])) {echo "checked";} ?>>
				<span class="check--select"></span>
				<label for="<?php echo $rand . $term->term_id; ?>" class="custom-control-label"><?php echo $term->name; ?></label>
			</div>
		<?php } ?>
	</div>
	<a href="#" class="more-or-less"><?php _e('Show More', 'directorist'); ?></a>
</div>