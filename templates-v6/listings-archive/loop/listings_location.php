<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<p><?php directorist_icon( $icon );?><?php echo !empty( $label ) ? '<span class="listings_data_list_label">' .esc_html( $label ). '</span>' : ''; ?><span class="listings_data_list_value"><?php echo $listings->get_the_location(); ?></span></p>