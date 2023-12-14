<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$alignment =  !empty( $data['align'] ) ? $data['align'] : '' ;
?>
<div class="directorist-thumb-listing-author directorist-alignment-<?php echo esc_attr( $alignment ) ?>">
	<a href="<?php echo esc_url( $listings->loop['author_link'] ); ?>" aria-label="Author Image" class="<?php echo esc_attr( $listings->loop['author_link_class'] ); ?>">
		<?php if ($listings->loop['u_pro_pic']) { ?>
			<img src="<?php echo esc_url($listings->loop['u_pro_pic'][0]); ?>" alt="<?php esc_attr_e( 'Author Image', 'directorist' );?>">
			<?php
		}
		else {
			echo wp_kses_post( $listings->loop['avatar_img'] );
		}
		?>
	</a>
</div>