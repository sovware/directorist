<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div <?php $listings->wrapper_class(); $listings->data_atts(); ?>>
	<div class="directorist-archive-contents__top">
		<?php if ( $listings->has_filters_button ) : ?>
            <?php $listings->mobile_view_filter_template(); ?>
        <?php endif; ?>

		<?php
			$listings->directory_type_nav_template();
			$listings->header_bar_template();
			$listings->full_search_form_template();
		?>
	</div>
	<div class="directorist-archive-contents__listings">
		<?php
			$listings->archive_view_template();
		?>
	</div>

</div>