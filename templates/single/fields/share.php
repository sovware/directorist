<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-listing-action directorist-social-share directorist-tooltip" data-label="<?php esc_html_e( 'Share', 'directorist' ); ?>">

	<?php directorist_icon( $icon );?>

	<ul class="directorist-social-share-links">
		<?php foreach ( $listing->social_share_data() as $social ): ?>
			<li class="directorist-social-links__item">
				<a href="<?php echo esc_url( $social['link'] ? $social['link'] : '#' );?>" target="_blank" class="<?php echo esc_attr( $social['icon'] ); ?>"><?php directorist_icon( $social['icon'] ); ?><?php echo esc_html( $social['title'] );?></a>
			</li>
		<?php endforeach; ?>
	</ul>

</div>