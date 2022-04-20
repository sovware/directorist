<?php defined( 'ABSPATH' ) || exit; ?>

<div class="csv-action-btns">
	<div class="csv-center csv-export">
		<input type="button" value="<?php esc_attr_e( 'Download a sample CSV', 'directorist' ); ?>" class="button-secondary" name="atbdp_ie_download_sample" id="atbdp_ie_download_sample" data-sample-csv="<?php echo $args['download_link']; ?>">
	</div>
</div>

<div class="csv-action-steps">
	<ul>
        <?php foreach( $args['nav_menu'] as $nav_menu_index => $nav_menu_item ) :
            $nav_menu_item['step_count'] = $nav_menu_index + 1;
			$args['controller']->importer_header_nav_menu_item_template( $nav_menu_item );
        endforeach; ?>
	</ul>
</div>