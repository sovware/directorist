<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-dropdown directorist-viewas-dropdown">

	<a class="directorist-dropdown-toggle" href="#"><?php echo esc_html( $listings->view_as_text ); ?><span class="directorist-icon-caret"></span></a>

	<div class="directorist-dropdown-menu">

		<?php foreach ( $listings->get_view_as_link_list() as $key => $value ): ?>

			<a class="directorist-dropdown-item <?php echo esc_attr($value['active_class']);?>" href="<?php echo esc_attr($value['link']);?>"><?php echo esc_html($value['label']);?></a>

		<?php endforeach; ?>

	</div>

</div>