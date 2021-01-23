<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="directorist-single-info directorist-single-info-web">
	<div class="directorist-single-info-label"><?php directorist_icon( $icon );?><?php echo esc_html( $data['label'] ); ?></div>
	<div class="directorist-single-info-value"><a target="_blank" href="<?php echo esc_url($value); ?>"<?php echo !empty($data['use_nofollow']) ? 'rel="nofollow"' : ''; ?>><?php echo esc_html($value); ?></a></div>
</div>