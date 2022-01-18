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
			foreach ( $listings->view_as_dropdown_data() as $key => $item ) {
				$active_class = ( $key == $listings->get_current_view() ) ? 'active' : '';
				?>

				<a class="directorist-dropdown__links--single <?php echo esc_attr( $active_class );?>" href="<?php echo esc_attr( $item['link'] ); ?>"><?php echo esc_html( $item['label'] );?></a>

				<?php
			}
			?>

	</div>

</div>