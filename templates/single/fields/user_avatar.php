<?php
/**
 * @author  wpWax
 * @since   7.7.0
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="map-listing-card-single__author">
	<a href="<?php echo esc_url( $author_link ); ?>" aria-label="<?php echo esc_attr( $author_full_name ); ?>">
		<?php if ( $u_pro_pic ) { ?>
			<img src="<?php echo esc_url($u_pro_pic[0]); ?>" alt="<?php esc_attr_e( 'Author Image', 'directorist' );?>">
			<?php
		}
		else {
			echo wp_kses_post( $avatar_img );
		}
		?>
	</a>
</div>