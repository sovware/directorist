<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<p><?php directorist_icon( $icon );?><?php echo !empty( $label ) ? '<span class="listings_data_list_label">' .esc_html( $label ). '</span>' : ''; ?><span class="listings_data_list_value"><?php echo esc_html( $value ); ?></span></p>