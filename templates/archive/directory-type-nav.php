<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
$get_current_url_type = isset( $_GET['directory_type'] ) ? $_GET['directory_type'] : '';
?>

<?php do_action( 'directorist_before_listing_types', $listings ); ?>

<div class="<?php Helper::directorist_container_fluid(); ?>">

	<div class="directorist-type-nav">
		<ul class="directorist-type-nav__list">
			<?php if( ! empty( $all_types ) ) { ?>
				<li class="list-inline-item <?php echo ( $listings->current_listing_type == 'all' || 'all' == $get_current_url_type ) ? 'current' : ''; ?>"><a class="directorist-type-nav__link" href="<?php echo esc_url( add_query_arg('directory_type', 'all' ) ); ?>"><span class=""></span> <?php _e( 'All', 'directorist' ); ?></a></li>
			<?php } ?>
			<?php foreach ( $listings->listing_types as $id => $value ): ?>

				<li class="<?php echo ( $listings->current_listing_type == $value['term']->term_id && 'all' != $get_current_url_type ) ? 'current': ''; ?>">
					<a class="directorist-type-nav__link" href="<?php echo esc_url( add_query_arg('directory_type', $value['term']->slug ) ); ?>"><span class="<?php echo esc_html( $value['data']['icon'] );?>"></span> <?php echo esc_html( $value['name'] );?></a>
				</li>
				
			<?php endforeach; ?>
			
		</ul>
	</div>

</div>