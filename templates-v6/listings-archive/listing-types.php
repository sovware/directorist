<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
$get_current_url_type = isset( $_GET['directory_type'] ) ? $_GET['directory_type'] : '';
?>
<div class="directorist-listing-types">
	<div class="<?php echo apply_filters('atbdp_add_listing_container_fluid', 'container-fluid'); ?>">
		<ul class="list-inline">
			<?php if( ! empty( $all_types ) ) { ?>
				<li class="list-inline-item <?php echo ( $listings->current_listing_type == 'all' || 'all' == $get_current_url_type ) ? 'current' : ''; ?>"><a class="directorist-listing-types-link" href="<?php echo esc_url( add_query_arg('directory_type', 'all' ) ); ?>"><span class=""></span> <?php _e( 'All', 'directorist' ); ?></a></li>
			<?php } ?>
			<?php foreach ( $listings->listing_types as $id => $value ): ?>
				<li class="list-inline-item <?php echo ( $listings->current_listing_type == $value['term']->term_id && 'all' != $get_current_url_type ) ? 'current': ''; ?>"><a class="directorist-listing-types-link" href="<?php echo esc_url( add_query_arg('directory_type', $value['term']->slug ) ); ?>"><span class="<?php echo esc_html( $value['data']['icon'] );?>"></span> <?php echo esc_html( $value['name'] );?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>