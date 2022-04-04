<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-category-top">

	<h3><?php echo esc_html( $title ); ?></h3>

	<ul>
		<?php 
			$count = 0;
			foreach ( $top_categories as $cat ): ?>
			
				<?php if( $searchform->popular_cat_num > $count ) { ?>

					<li>
						<a href="<?php echo ATBDP_Permalink::atbdp_get_category_page( $cat ); ?>">
							<span class="<?php echo esc_attr( $searchform->category_icon_class( $cat ) ); ?>"></span>
							<p><?php echo esc_html( $cat->name ); ?></p>
						</a>
					</li>

				<?php } ?>

		<?php 
			$count++;
			endforeach; ?>
	</ul>
	
</div>