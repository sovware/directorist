<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$search_form = directorist()->search_form;
?>

<div class="<?php Helper::directorist_container_fluid(); ?>">
	<ul class="directorist-listing-type-selection">
		<?php foreach ( $search_form->get_listing_type_data() as $id => $value ): ?>

			<li class="directorist-listing-type-selection__item"><a class="search_listing_types directorist-listing-type-selection__link<?php echo $search_form->get_default_listing_type() == $id ? '--current': ''; ?>" data-listing_type="<?php echo esc_attr( $value['term']->slug );?>" href="#"><span class="<?php echo esc_html( $value['data']['icon'] );?>"></span> <?php echo esc_html( $value['name'] );?></a></li>

		<?php endforeach; ?>
	</ul>
</div>