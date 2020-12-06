<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="directorist-listing-types">
	<div class="<?php echo apply_filters('atbdp_add_listing_container_fluid', 'container-fluid'); ?>">
		<ul class="list-inline">
			<?php foreach ( $listing_types as $id => $value ): ?>
				<li class="list-inline-item <?php echo $listings->current_listing_type == $id ? 'current': ''; ?>"><a class="search_listing_types" data-listing_type="<?php echo esc_attr( $id );?>" href="#"><span class="<?php echo esc_html( $value['data']['icon'] );?>"></span> <?php echo esc_html( $value['name'] );?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>