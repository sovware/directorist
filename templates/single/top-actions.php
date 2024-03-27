<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.8.4
 */

use \Directorist\Directorist_Single_Listing;

if ( ! defined( 'ABSPATH' ) ) exit;

$listing = Directorist_Single_Listing::instance();
?>

<div class="directorist-signle-listing-top directorist-flex directorist-align-center directorist-justify-content-between">

	<?php if ( $listing->current_user_is_author() ): ?>

		<?php if ( $listing->display_back_link() && ! $listing->has_redirect_link() && wp_get_referer() ): ?>

		<a href="<?php echo esc_url( wp_get_referer() ) ?>" class="directorist-return-back"><?php directorist_icon( 'las la-angle-left' ); ?> <?php esc_html_e( 'Go Back', 'directorist'); ?></a>

	<?php endif; ?>

	<div>

		<?php if ( $listing->submit_link() ): ?>
			<a href="<?php echo esc_url( $listing->submit_link() ); ?>" class="directorist-btn directorist-btn-sm directorist-btn-success directorist-signle-listing-top__btn-continue"><?php esc_html_e( 'Continue', 'directorist' ); ?></a>
		<?php endif; ?>

		<a href="<?php echo esc_url( $listing->edit_link() ) ?>" class="directorist-btn directorist-btn-sm directorist-btn-outline-light directorist-signle-listing-top__btn-edit">
			<?php directorist_icon( 'las la-edit' ); ?>
			<?php esc_html_e( 'Edit', 'directorist' ); ?>
		</a>

	</div>

	<?php elseif( $listing->display_back_link() && wp_get_referer() ): ?>

		<a href="<?php echo esc_url( wp_get_referer() ) ?>" class="directorist-return-back"><?php directorist_icon( 'las la-angle-left' ); ?> <?php esc_html_e( 'Go Back', 'directorist'); ?></a>

	<?php endif; ?>

</div>