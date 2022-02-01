<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-thumb-listing-author directorist-alignment-<?php echo esc_attr( $listings->avatar_alignment() ) ?>">

	<a href="<?php echo esc_url( $listings->loop_author_link() ); ?>">

		<?php if ( $listings->loop_author_img_src() ) { ?>
			<img src="<?php echo esc_url( $listings->loop_author_img_src() ); ?>" alt="<?php echo esc_attr( $listings->loop_author_name() );?>">
			<?php
		}
		else {
			echo $listings->loop_author_avatar();
		}
		?>

	</a>

</div>