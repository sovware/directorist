<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Directorist_Single_Listing;
use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listing = Directorist_Single_Listing::instance();

get_header( 'directorist' );
?>

<div class="<?php Helper::directorist_container(); ?>">

	<?php $listing->notice_template(); ?>

	<div class="<?php Helper::directorist_row(); ?>">

		<div class="<?php $listing->content_col_class(); ?>">

			<?php Helper::get_template( 'single/top-actions' ); ?>
			<?php Helper::get_template( 'single-contents' ); ?>

		</div>

		<?php Helper::get_template( 'single/sidebar' ); ?>

	</div>

</div>

<?php
get_footer( 'directorist' );