<?php

/**
 * This template displays the ACADP categories list.
 */
?>

    <div class="atbdp atbdp-categories atbdp-text-list">
    <?php

		$span = 'col-md-' . floor( 12 /  $categories_settings['columns'] );
		--$categories_settings['depth'];
		$i = 0;

		foreach( $terms as $term ) {
            $categories_settings['term'] = $term;
			$categories_settings['term_id'] = $term->term_id;

			$count = 0;
			if( ! empty( $categories_settings['hide_empty'] ) || ! empty( $categories_settings['show_count'] ) ) {
				$count = atbdp_listings_count_by_category( $term->term_id );

				if( ! empty( $categories_settings['hide_empty'] ) && 0 == $count ) continue;
			}

			if( $i % $categories_settings['columns'] == 0 ) {
				echo '<div class="row atbdp-no-margin">';
			}

			echo '<div class="' . $span . '"><div class="atbd_category_wrapper">';
			echo '<a href=" ' .ATBDP_Permalink::get_category_archive($term) . ' ">';
			echo '<strong>' . $term->name . '</strong>';
			if( ! empty( $categories_settings['show_count'] ) ) {
				echo ' (' .  $count . ')';
			}
			echo '</a>';
			echo atbdp_list_categories( $categories_settings );
			echo '</div></div>';

			$i++;
			if( $i % $categories_settings['columns'] == 0 || $i == count( $terms ) ) {
				echo '</div>';
			}
		}
	?>
</div>
