<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$socials = get_post_meta( $listing->id, '_social', true);
?>
<div class="directorist-single-info directorist-single-info-socials">
	<?php if ( $data['label'] ): ?>
		<div class="directorist-single-info-label"><?php directorist_icon( $icon );?><?php echo esc_html( $data['label'] ); ?></div>
	<?php endif; ?>
	<div class="directorist-single-info-value">
		<div class="atbd_director_social_wrap">
			<?php foreach ( $socials as $link ) { ?>
				<a target='_blank' href="<?php echo esc_url($link['url']); ?>" class="<?php echo esc_attr($link['id']); ?>">
					<span class="<?php atbdp_icon_type(true);?>-<?php echo esc_attr($link['id']); ?>"></span>
				</a>
				<?php
			}
			?>
		</div>
	</div>
</div>