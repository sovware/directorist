<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 7.0.4
 */
?>
<h4 class="atbd_listing_title"><?php echo wp_kses_post( $listings->loop_get_title() );?></h4>

<?php if( !empty( $data['show_tagline'] ) && !empty( $listings->loop_get_tagline() ) ){ ?>

<p class="atbd_listing_tagline"><?php echo wp_kses_post( $listings->loop_get_tagline() );?></p>

<?php }?>