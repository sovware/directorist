<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-dropdown directorist-dropdown-js directorist-viewas-dropdown">

	<a class="directorist-dropdown__toggle directorist-dropdown__toggle-js directorist-btn directorist-btn-sm directorist-btn-px-15 directorist-btn-outline-primary directorist-toggle-has-icon" href="#"><?php echo esc_html( $listings->view_as_text() ); ?><span class="directorist-icon-caret"></span></a>

	<div class="directorist-dropdown__links directorist-dropdown__links-js">

			<?php
			$current = !empty( $_GET['view'] ) ? $_GET['view'] : '';

			foreach ( $listings->view_as_dropdown_items() as $item ) {
				$active_class = ( $item == $current ) ? 'active' : '';
				?>

				<a class="directorist-dropdown__links--single directorist-dropdown__links--single-js <?php echo esc_attr( $active_class );?>" data-link="<?php echo esc_attr( $listings->sort_by_dropdown_link( $item ) ); ?>"><?php echo esc_html( $listings->sort_by_dropdown_label( $item ) );?></a>

				<?php
			}
			?>



		<?php foreach ( $listings->view_as_dropdown_items() as $key => $value ): ?>

			<a class="directorist-dropdown__links--single <?php echo esc_attr($value['active_class']);?>" href="<?php echo esc_attr($value['link']);?>"><?php echo esc_html($value['label']);?></a>

		<?php endforeach; ?>

	</div>

</div>