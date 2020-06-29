<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="<?php echo esc_attr( $class ); ?>">
	<div class="atbdp-related-listing-header">
		<h4><?php echo esc_html( $title ); ?></h4>
	</div>
	<div class="atbd_margin_fix">
		<div class="related__carousel">
			<?php
			if ($listings->query->have_posts()) {
				$listings->loop_template('related-grid');
			}
			else { ?>
				<p class="atbdp_nlf"><?php esc_html_e('No listing found.', 'directorist'); ?></p>
				<?php
			}
			?>
		</div>
	</div>
</div>