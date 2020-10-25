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
			<?php foreach ( $listings->listing_types as $id => $name ): ?>
				<li class="list-inline-item <?php echo $listings->current_listing_type == $id ? 'current': ''; ?>"><a class="btn btn-primary" href="<?php echo esc_url( add_query_arg('listing_type', $id) ); ?>"><?php echo esc_html( $name );?></a></li>
			<?php endforeach; ?>
		</ul>		
	</div>
</div>