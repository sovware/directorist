<?php
/**
 * @template_description1
 *
 * This template can be overridden by copying it to yourtheme/directorist/ @template_description2
 *
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-types">
	<div class="<?php Helper::directorist_container(); ?>">
		<ul class="directorist-listing-type-list">
			<?php foreach ( $listings->listing_types as $id => $value ): ?>
				<li class="<?php echo $listings->current_listing_type == $value['term']->term_id ? 'current': ''; ?>">
					<a class="directorist-listing-types-link" href="<?php echo esc_url( add_query_arg('directory_type', $value['term']->slug ) ); ?>"><span class="<?php echo esc_html( $value['data']['icon'] );?>"></span> <?php echo esc_html( $value['name'] );?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>