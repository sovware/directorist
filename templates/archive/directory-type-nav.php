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

			<?php foreach ( $listings->allowed_directory_types() as $type ) : ?>

				<?php $current = ( $listings->current_directory_type_id() == $type->term_id ) ? 'current': '';?>

				<li class="<?php echo esc_attr( $current ); ?>">

					<a class="directorist-type-nav__link" href="<?php echo esc_url( $listings->directory_type_url( $type ) ); ?>">
						<span class="<?php echo esc_attr( $listings->directory_type_icon( $type ) );?>"></span>
						<?php echo esc_html( $listings->directory_type_name( $type ) );?>
					</a>

				</li>

			<?php endforeach; ?>

		</ul>

	</div>

</div>