<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-type-nav">
	<div class="<?php Helper::directorist_container(); ?>">
		<ul class="directorist-type-nav-list">
			<?php foreach ( $listings->listing_types as $id => $value ): ?>
				<li class="<?php echo $listings->current_listing_type == $value['term']->term_id ? 'current': ''; ?>">
					<a class="directorist-type-nav-link" href="<?php echo esc_url( add_query_arg('directory_type', $value['term']->slug ) ); ?>"><span class="<?php echo esc_html( $value['data']['icon'] );?>"></span> <?php echo esc_html( $value['name'] );?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>