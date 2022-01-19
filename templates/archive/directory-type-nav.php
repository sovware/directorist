<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.5.3
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;

do_action( 'directorist_before_listing_types', $listings );
?>

<div class="<?php Helper::directorist_container_fluid(); ?>">

	<div class="directorist-type-nav">

		<ul class="directorist-type-nav__list">

			<?php foreach ( $listings->get_listing_types() as $id => $value ) : ?>

				<li class="<?php echo ( ( $listings->get_current_listing_type() === $value['term']->term_id ) ? 'current': '' ); ?>">

					<a class="directorist-type-nav__link" href="<?php echo esc_url( directorist_get_directory_type_nav_url( $value['term']->slug ) ); ?>"><span class="<?php echo esc_attr( $value['data']['icon'] );?>"></span> <?php echo esc_html( $value['name'] );?></a>
				</li>

			<?php endforeach; ?>

		</ul>

	</div>

</div>