<?php
/**
 * @author  wpWax
 * @since   7.0.5
 * @version 7.0.5
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-info directorist-single-info-custom">

	<?php if ( !empty( $data['label'] ) ): ?>
		<div class="directorist-single-info__label">
			<?php if ( $icon ): ?>
				<span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
			<?php endif; ?>
			<span class="directorist-single-info__label--text"><?php echo esc_html( $data['label'] ); ?></span>
		</div>
	<?php endif; ?>

	<div class="directorist-single-info__value"><?php echo do_shortcode( $value ); ?></div>

</div>