<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.0
 */

use \Directorist\Directorist_Single_Listing;
use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listing = Directorist_Single_Listing::instance();
?>

<div class="directorist-single-contents-area directorist-w-100" data-id="<?php echo esc_attr( $listing->id ?? ''); ?>">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<?php $listing->notice_template(); ?>

		<div class="<?php Helper::directorist_row(); ?>">

			<div class="<?php Helper::directorist_single_column(); ?>">

				<?php 
				
				$disable_single_listing = get_directorist_option( 'disable_single_listing') ? true : false;

				if( !$disable_single_listing ){ ?>

					<?php if ( $listing->single_page_enabled() ): ?>

						<div class="directorist-single-wrapper">

							<?php
							// Output already filtered
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo $listing->single_page_content();
							?>

						</div>

					<?php else: ?>

						<div class="directorist-single-wrapper">

							<?php
							$listing->header_template();

							foreach ( $listing->content_data as $section ) {
								$listing->section_template( $section );
							}
							?>

						</div>

					<?php endif; ?>
				<?php } else { ?>
					<div class="directorist-alert directorist-alert-warning directorist-single-listing-notice">

						<div class="directorist-alert__content">

							<span><?php esc_html_e( 'Single listing view is disabled', 'directorist' ); ?></span>

						</div>

					</div>
				<?php } ?>

			</div>

			<?php Helper::get_template( 'single-sidebar' ); ?>

		</div>
	</div>
</div>