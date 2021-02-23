<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="row">
	<div class="col-md-12">
		<div class="atbd_author_listings_area">

			<?php if ($display_title){ ?>
			<h1><?php esc_html_e("Author Listings", 'directorist'); ?></h1>
			<?php } ?>

			<?php if ( $display_cat_filter ) { ?>
				<div class="atbd_author_filter_area">
					<?php
                    /*
                     * @since 6.2.3
                     */
                    do_action('atbpd_before_author_listings_category_dropdown', $listings->query_results);
					
					$get_current_url_type = isset( $_GET['directory_type'] ) ? $_GET['directory_type'] : '';
                    ?>

					<div class="directorist-listing-types">
						<div class="<?php echo apply_filters('atbdp_add_listing_container_fluid', 'container-fluid'); ?>">
							<ul class="list-inline">
								<?php foreach ( get_listing_types() as $id => $value ): ?>
									<li class="list-inline-item <?php echo ( default_directory_type() == $value['term']->term_id && 'all' != $get_current_url_type ) ? 'current': ''; ?>"><a class="directorist-listing-types-link" href="<?php echo esc_url( add_query_arg('directory_type', $value['term']->slug ) ); ?>"><span class="<?php echo esc_html( $value['data']['icon'] );?>"></span> <?php echo esc_html( $value['name'] );?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>

                    <div class="atbd_dropdown">
                    	<a class="atbd_dropdown-toggle" href="#" id="dropdownMenuLink"><?php esc_html_e("Filter by category", 'directorist'); ?> <span class="atbd_drop-caret"></span></a>

                    	<div class="atbd_dropdown-menu atbd_dropdown-menu--lg" aria-labelledby="dropdownMenuLink">
                    		<?php foreach ($categories as $category) {
                    			$active = (isset($_GET['category']) && ($category->slug == $_GET['category'])) ? 'active' : '';
                    			printf('<a class="atbd_dropdown-item %s" href="%s">%s</a>', $active, add_query_arg('category', $category->slug), $category->name);
                    		}
                    		?>
                    	</div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php 
/**
 * @since 6.6
 */
do_action( 'atbdp_author_listings_before_loop' ); ?>
<div class="atbd_authors_listing">
	<?php if ($display_listings) { ?>
		<div class="row"<?php echo esc_attr($listings->masonary_grid_attr()); ?>>
			<?php $listings->setup_loop( ['template' => 'grid'] ); ?>
		</div>
		<?php
		if ( $display_pagination ) {
			echo atbdp_pagination( $listings->query_results );
		}
	}
	?>
</div>