<div id="directorist" class="atbd_wrapper">
    <div class="atbdp atbdp-categories atbdp-text-list">
        <?php
        $span = 'col-md-' . floor( 12 /  $locations_settings['columns'] );
        --$locations_settings['depth'];
        $i = 0;

        foreach( $terms as $term ) {
            $locations_settings['term'] = $term;
			$locations_settings['term_id'] = $term->term_id;
			$child_location = get_term_children($term->term_id,ATBDP_LOCATION);
            $plus_icon = !empty($child_location) ? '<span class="expander">+</span>' : '';
			$count = 0;
			if( ! empty( $locations_settings['hide_empty'] ) || ! empty( $locations_settings['show_count'] ) ) {
				$count = atbdp_listings_count_by_location( $term->term_id );

				if( ! empty( $locations_settings['hide_empty'] ) && 0 == $count ) continue;
			}

			if( $i % $locations_settings['columns'] == 0 ) {
				echo '<div class="row atbdp-no-margin">';
			}

			echo '<div class="' . $span . ' col-sm-6"><div class="atbd_category_wrapper">';
			echo '<a href=" ' .ATBDP_Permalink::atbdp_get_location_page($term) . ' ">';
			echo '<span>' . $term->name . '</span>';
			if( ! empty( $locations_settings['show_count'] ) ) {
                $expired_listings = atbdp_get_expired_listings(ATBDP_LOCATION, $term->term_id);
                $number_of_expired = $expired_listings->post_count;
                $number_of_expired = !empty($number_of_expired)?$number_of_expired:'0';
                $totat = ($count)?($count-$number_of_expired):$count;
				echo ' (' .  $totat . ')';
			}
			echo "</a>$plus_icon";
			echo atbdp_list_locations( $locations_settings );
			echo '</div></div>';

			$i++;
			if( $i % $locations_settings['columns'] == 0 || $i == count( $terms ) ) {
				echo '</div>';
			}

		}
	?>
    </div>
</div>

