<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-user-dashboard">

	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<?php $dashboard->notice_template(); ?>

		<?php if ( $dashboard->display_title() ): ?>
			<h2><?php esc_html_e( 'My Dashboard', 'directorist' ); ?></h2>
		<?php endif; ?>

		<div class="directorist-user-dashboard__toggle">
			<a href="#" class="directorist-user-dashboard__toggle__link"><?php directorist_icon( 'las la-bars' ); ?></a>
		</div>

		<div class="directorist-user-dashboard__contents directorist-tab directorist-tab-content-grid-fix">
			<?php $dashboard->navigation_template(); ?>
			<?php $dashboard->main_contents_template(); ?>
		</div>

	</div>

	<div class="directorist-shade"></div>

</div>