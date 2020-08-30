<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbd_submit_btn_wrapper">
	<?php if ($searchform->has_more_filters_button): ?>
		<a href="" class="more-filter btn btn-outline btn-lg btn-outline-primary"><?php echo $more_filters_icon;echo esc_html( $searchform->more_filters_text );?></a>
	<?php endif ?>

	<?php if ($searchform->has_search_button): ?>
		<div class="atbd_submit_btn">
			<button type="submit" class="btn btn-primary btn-lg btn_search"><?php echo $search_button_icon;echo esc_html( $searchform->search_button_text );?></button>
		</div>
	<?php endif; ?>
</div>