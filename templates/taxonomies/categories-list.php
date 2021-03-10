<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

use \Directorist\Helper;

$columns = floor( 12 / $taxonomy->columns );
?>
<div id="directorist" class="atbd_wrapper atbdp atbdp-categories atbdp-text-list directorist-w-100">
	<?php
    /**
     * @since 5.6.6
     */
    do_action( 'atbdp_before_all_categories_loop', $taxonomy );
    ?>
	<div class="<?php Helper::directorist_container_fluid(); ?>">
	<div class="<?php Helper::directorist_row(); ?> atbdp-no-margin">
		<?php
		if( $categories ) {
			foreach ($categories as $category) {
				$plus_icon = $category['has_child'] ? '<span class="expander">+</span>' : '';
				?>
				<div class="<?php Helper::directorist_column( $columns ); ?>">
					<div class="atbd_category_wrapper">
						<a href="<?php echo esc_url($category['permalink']);?>" class="atbd_parent_cat"><span><?php echo esc_html($category['name']);?></span><?php echo $category['list_count_html'];?></a><?php echo $plus_icon;?>
						<?php echo $category['subterm_html'];?>
					</div>
				</div>
				<?php
			}
		} else {
			_e('<p>No Results found!</p>', 'directorist');
		}
		?>
	</div>
	</div>
	<?php
	/**
     * @since 5.6.6
     */
    do_action( 'atbdp_after_all_categories_loop' );
    ?>

<!-- Category style 03 -->
<!-- <div class="directorist-category-child">
		<div class="directorist-row">
			<div class="directorist-col-lg-3 directorist-col-md-4 directorist-col-sm-6">
				<div class="directorist-category-child__card">
					<div class="directorist-category-child__card__header">
						<a href="" class=""><i class="la la-bed"></i> Restaurant</a>
					</div>
					<div class="directorist-category-child__card__body">
						<ul>
							<li><a href="">Cleaning <span class="directorist-category-child-count">20</span></a></li>
							<li><a href="">Cleaning <span class="directorist-category-child-count">20</span></a></li>
							<li><a href="">Cleaning <span class="directorist-category-child-count">20</span></a></li>
							<li><a href="">Cleaning <span class="directorist-category-child-count">20</span></a></li>
							<li><a href="">Cleaning <span class="directorist-category-child-count">20</span></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="directorist-col-lg-3 directorist-col-md-4 directorist-col-sm-6">
				<div class="directorist-category-child__card">
					<div class="directorist-category-child__card__header">
						<a href="" class=""><i class="la la-heart"></i> Restaurant</a>
					</div>
					<div class="directorist-category-child__card__body">
						<ul>
							<li><a href="">Cleaning <span class="directorist-category-child-count">20</span></a></li>
							<li><a href="">Cleaning <span class="directorist-category-child-count">20</span></a></li>
							<li><a href="">Cleaning <span class="directorist-category-child-count">20</span></a></li>
							<li><a href="">Cleaning <span class="directorist-category-child-count">20</span></a></li>
							<li><a href="">Cleaning <span class="directorist-category-child-count">20</span></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div> -->
</div>