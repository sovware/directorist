<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="directorist" class="directorist atbd_wrapper dashboard_area alignwide">

	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<?php $dashboard->notice_template(); ?>

		<?php if ( $dashboard->display_title() ): ?>
			<h2><?php esc_html_e( 'My Dashboard', 'directorist' ); ?></h2>
		<?php endif; ?>

		<div class="atbd-dashboard-nav-toggle-icon"><a href="#" class="atbd-dashboard-nav-toggler"><i class="la la-bars"></i></a></div>
		
		<div class="atbd_dashboard_wrapper atbd_tab">

			<?php $dashboard->navigation_template(); ?>
			<?php $dashboard->main_contents_template(); ?>

		</div>

	</div>

</div>