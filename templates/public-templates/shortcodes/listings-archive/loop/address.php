<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$a = get_post_meta( get_the_id(), '_listings_location', true );
// var_dump($a);


?>
<p><?php directorist_icon( $icon );?><?php echo esc_html( $value ); ?></p>