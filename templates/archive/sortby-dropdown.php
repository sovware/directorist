<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-dropdown directorist-dropdown-js directorist-sortby-dropdown">

	<button class="directorist-dropdown__toggle directorist-dropdown__toggle-js directorist-btn directorist-btn-sm directorist-toggle-has-icon"><?php echo esc_html( $listings->sort_by_text ); ?><span class="directorist-icon-caret"></span></button>

	<div class="directorist-dropdown__links directorist-dropdown__links-js directorist-dropdown__links__right">

		<form id="directorsit-listing-sort" method="post" action="#">
			<?php
			$current_order = !empty($_GET['sort']) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : '';
			foreach ($listings->get_sort_by_link_list() as $key => $value) {
				$active_class = ( $value['key'] == $current_order ) ? ' active' : '';
				?>
				<a href="#" class="directorist-dropdown__links__single directorist-dropdown__links__single-js <?php echo esc_attr( $active_class );?>" data-link="<?php echo esc_attr( $value['link'] ); ?>"><?php echo esc_html($value['label']);?></a>
				<?php
			}
			?>
		</form>

	</div>

</div>