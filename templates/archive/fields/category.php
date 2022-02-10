<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings   = directorist()->listings;
$categories = $listings->loop_get_categories();
$count      = count( $categories );
?>

<div class="directorist-listing-category">

	<?php if ( $count > 0 ): ?>

		<a href="<?php echo esc_url( $listings->category_link( $categories[0] ) ); ?>"><span class="<?php echo esc_attr( $listings->category_icon( $categories[0] ) );?>"></span><?php echo esc_html( $categories[0]->name ); ?></a>

		<?php if( $count > 1 ): ?>

			<div class="directorist-listing-category__popup">

				<span class="directorist-listing-category__extran-count">+<?php echo esc_html( $count ); ?></span>

				<div class="directorist-listing-category__popup__content">

					<?php
					foreach ( array_slice( $categories, 1 ) as $category ) {
						printf(
							'<a href="%s"><span class="%s"></span>%s</a>',
							esc_url( $listings->category_link( $category ) ),
							esc_attr( $listings->category_icon( $category ) ),
							esc_html( $category->name )
						);
					}
					?>

				</div>

			</div>

		<?php endif; ?>

	<?php else: ?>

		<a href="#"><span class="<?php echo esc_attr( $listings->default_category_icon() );?>"></span><?php esc_html_e( 'Uncategorized', 'directorist' ); ?></a>

	<?php endif; ?>

</div>