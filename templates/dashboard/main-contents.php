<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$counter = 1;
?>

<div class="directorist-user-dashboard__tab-content directorist-tab__content">

	<?php foreach ( $dashboard->dashboard_tabs() as $key => $value ): ?>

		<div class="directorist-tab__pane <?php echo ( $counter == 1 ) ? 'directorist-tab__pane--active' : ''; ?>" id="<?php echo esc_attr( $key ); ?>">
			<?php
			// Contents are coming from dashboard template files which are escaped already
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $value['content'];
			?>
		</div>
		<?php
			if (!empty($value['after_content_hook'])) {
				do_action($value['after_content_hook']);
			}
			?>
		<?php do_action( 'directorist_dashboard_contents', $key, $dashboard ); ?>

		<?php $counter++; ?>

	<?php endforeach; ?>

	<?php do_action( 'directorist_after_dashboard_contents', $dashboard ); ?>

</div>