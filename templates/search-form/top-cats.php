<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$search_form = directorist()->search_form;
?>

<div class="directorist-listing-category-top">

	<h3><?php echo esc_html( $title ); ?></h3>

	<ul>
		<?php foreach ( $top_categories as $cat ): ?>

			<li>
				<a href="<?php echo ATBDP_Permalink::atbdp_get_category_page( $cat ); ?>">
					<span class="<?php echo esc_attr( $search_form->category_icon_class( $cat ) ); ?>"></span>
					<p><?php echo esc_html( $cat->name ); ?></p>
				</a>
			</li>

		<?php endforeach; ?>
	</ul>

</div>