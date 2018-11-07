<?php

/**
 * This template displays the ACADP categories list.
 */
?>

<div class="atbdp atbdp-categories atbdp-text-list">
    <?php

		$span = 'col-md-' . floor( 12 /  $locations_settings['columns'] );
		--$locations_settings['depth'];
		$i = 0;

		foreach( $terms as $term ) {
            $locations_settings['term'] = $term;
			$locations_settings['term_id'] = $term->term_id;

			$count = 0;
			if( ! empty( $locations_settings['hide_empty'] ) || ! empty( $locations_settings['show_count'] ) ) {
				$count = atbdp_listings_count_by_location( $term->term_id );

				if( ! empty( $locations_settings['hide_empty'] ) && 0 == $count ) continue;
			}

			if( $i % $locations_settings['columns'] == 0 ) {
				echo '<div class="row atbdp-no-margin">';
			}

			echo '<div class="' . $span . '">';
			echo '<a href=" ' .ATBDP_Permalink::get_location_archive($term) . ' ">';
			echo '<strong>' . $term->name . '</strong>';
			if( ! empty( $locations_settings['show_count'] ) ) {
				echo ' (' .  $count . ')';
			}
			echo '</a>';
			echo atbdp_list_locations( $locations_settings );
			echo '</div>';

			$i++;
			if( $i % $locations_settings['columns'] == 0 || $i == count( $terms ) ) {
				echo '</div>';
			}

		}
	?>
</div>
