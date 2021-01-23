<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="atbd_submit_btn_wrapper">
	<?php if ($searchform->has_more_filters_button): ?>
		<div class="atbd_filter_btn">
			<a href="" class="more-filter btn btn-lg"><?php echo $more_filters_icon;echo esc_html( $searchform->more_filters_text );?></a>
		</div>
	<?php endif ?>

	<?php if ($searchform->has_search_button): ?>
		<div class="atbd_submit_btn">
			<button type="submit" class="btn btn-lg btn_search"><?php echo $search_button_icon;echo esc_html( $searchform->search_button_text );?></button>
		</div>
	<?php endif; ?>
</div>