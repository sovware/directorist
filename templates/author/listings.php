<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbd_author_listings_area">

	<h2><?php esc_html_e( 'Author Listings' , 'directorist'); ?></h2>

	<?php if ( $author->cat_filter_enabled() ): ?>

		<div class="atbd_dropdown">

			<a class="atbd_dropdown-toggle" href="#" id="dropdownMenuLink"><?php esc_html_e( 'Filter by category', 'directorist'); ?> <span class="atbd_drop-caret"></span></a>

			<div class="atbd_dropdown-menu atbd_dropdown-menu--lg" aria-labelledby="dropdownMenuLink">
				<?php
				foreach ($author->get_listing_categories() as $category) {
					$active_class = ( isset($_GET['category']) && ($category->slug == $_GET['category']) ) ? 'active' : '';
					$link = add_query_arg( 'category', $category->slug );
					printf('<a class="directoriet-dropdown-item %s" href="%s">%s</a>', $active_class, $link, $category->name);
				}
				?>
			</div>

		</div>

	<?php endif; ?>

</div>

<div class="atbd_authors_listing <?php echo esc_attr( $listings->grid_view_class() ); ?>">

		<div class="<?php Helper::directorist_row(); ?>">

			<?php foreach ( $listings->post_ids() as $listing_id ): ?>

				<div class="<?php Helper::directorist_column( $listings->columns ); ?>">
					<?php $listings->loop_template( 'grid', $listing_id ); ?>
				</div>
				
			<?php endforeach; ?>

		</div>

		<?php
		if ( $author->listing_pagination_enabled() ) {
			$listings->pagination();
		}
		?>
		
</div>