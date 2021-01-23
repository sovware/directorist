<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="directorist-single-info directorist-single-info-email">
	<div class="directorist-single-info-label"><?php directorist_icon( $icon );?><?php echo esc_html( $data['label'] ); ?></div>
	<div class="directorist-single-info-value"><a target="_top" href="mailto:<?php echo esc_html($value); ?>"><?php echo esc_html($value); ?></a></div>
</div>