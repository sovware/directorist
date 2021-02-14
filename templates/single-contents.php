<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

use \Directorist\Directorist_Single_Listing;
use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listing = Directorist_Single_Listing::instance();
?>

<div class="directorist-single-contents-area">

	<?php $listing->notice_template(); ?>

	<div class="<?php Helper::directorist_row(); ?>">

		<div class="<?php $listing->content_col_class(); ?>">

			<?php Helper::get_template( 'single/top-actions' ); ?>

			<div class="directorist-single-wrapper">

				<?php
				$listing->header_template();

				foreach ( $listing->content_data as $section ) {
					$listing->section_template( $section );
				}
				?>
				
			</div>

		</div>

		<?php Helper::get_template( 'single-sidebar' ); ?>

	</div>

</div>