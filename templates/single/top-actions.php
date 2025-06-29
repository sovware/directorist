<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

use \Directorist\Directorist_Single_Listing;

if ( ! defined( 'ABSPATH' ) ) exit;

$listing = Directorist_Single_Listing::instance();
?>

<div class="directorist-single-listing-top directorist-flex directorist-align-center directorist-justify-content-between">
	<?php if( $listing->display_back_link() ): ?>

	<a href="javascript:history.back()" class="directorist-single-listing-action directorist-return-back directorist-btn__back directorist-btn directorist-btn-sm directorist-btn-light"><?php directorist_icon( 'las la-arrow-left' ); ?> <span class="directorist-single-listing-action__text"><?php esc_html_e( 'Go Back', 'directorist'); ?></span> </a>

	<?php endif; ?>

	<div class="directorist-single-listing-quick-action directorist-flex directorist-align-center directorist-justify-content-between">

		<?php if ( $listing->submit_link() ): ?>
			<div class="directorist-single-listing-top__btn-wrapper">
				<a href="<?php echo esc_url( $listing->submit_link() ); ?>" class="directorist-single-listing-action directorist-btn directorist-btn-sm directorist-btn-light directorist-single-listing-top__btn-continue"><span class="directorist-single-listing-action__text"><?php esc_html_e( 'Continue to Publish', 'directorist' ); ?></span> </a>
			</div>
		<?php endif; ?>
		
		<?php if ( $listing->current_user_is_author() ): ?>
			<a href="<?php echo esc_url( $listing->edit_link() ) ?>" class="directorist-single-listing-action directorist-btn directorist-btn-sm directorist-btn-light directorist-single-listing-top__btn-edit">
				<?php directorist_icon( 'las la-pen' ); ?>
				<span class="directorist-single-listing-action__text"><?php esc_html_e( 'Edit', 'directorist' ); ?></span>	
			</a>
		<?php endif; ?>

		<?php $listing->quick_actions_template(); ?>

	</div>

</div>