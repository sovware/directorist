<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<ul class="directorist-listing-type-selection">
	<?php foreach ( $searchform->get_listing_type_data() as $id => $value ): ?>

		<li class="directorist-listing-type-selection__item"><a class="search_listing_types directorist-listing-type-selection__link<?php echo $searchform->get_default_listing_type() == $id ? '--current': ''; ?>" data-listing_type="<?php echo esc_attr( $value['term']->slug );?>" data-listing_type_id="<?php echo esc_attr( $id );?>" href="#"><?php directorist_icon( $value['data']['icon'] ); ?> <?php echo esc_html( $value['name'] );?></a></li>

	<?php endforeach; ?>
</ul>