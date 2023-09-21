<?php
/**
 * Multi directory navigation template.
 * Mostly used on listings archive view.
 *
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$current_directory_type = ( ! empty( $_GET['directory_type'] ) ? sanitize_text_field( wp_unslash( $_GET['directory_type'] ) ) : '' );

do_action( 'directorist_before_listing_types', $listings );
?>
<div class="directorist-type-nav">
	<ul class="directorist-type-nav__list">

		<?php if ( ! empty( $all_types ) ) : ?>

			<li class="list-inline-item <?php echo ( $listings->current_listing_type === 'all' || 'all' === $current_directory_type ) ? 'directorist-type-nav__list__current' : ''; ?>">
				<a class="directorist-type-nav__link" href="<?php echo esc_url( directorist_get_directory_type_nav_url( 'all' ) ); ?>">
				<?php directorist_icon( 'fa fa-grip-horizontal' ); ?> <?php esc_html_e( 'All', 'directorist' ); ?></a>
			</li>

		<?php endif; ?>

		<?php foreach ( $listings->listing_types as $id => $value ) : ?>

			<li class="<?php echo ( ( $listings->current_listing_type === $value['term']->term_id && 'all' !== $current_directory_type ) ? 'directorist-type-nav__list__current': '' ); ?>">
				<a class="directorist-type-nav__link" href="<?php echo esc_url( directorist_get_directory_type_nav_url( $value['term']->slug ) ); ?>" data-listing_type="<?php echo esc_attr( $value['term']->slug ); ?>" data-listing_type_id="<?php echo esc_attr( $value['term']->term_id ); ?>"><?php directorist_icon( $value['data']['icon'] );?> <?php echo esc_html( $value['name'] );?></a>
			</li>

		<?php endforeach; ?>

	</ul>
</div>
