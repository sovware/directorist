<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

dvar_dump($data);

?>
<div class="atbd_info_title"><span class="<?php atbdp_icon_type(true);?>-map-marker"></span><?php echo esc_html( $address_label ); ?></div>
<div class="atbd_info"><?php echo $address_html; ?></div>