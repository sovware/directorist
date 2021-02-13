<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$counter = 1;
?>

<div class="atbd_tab-content">

	<?php foreach ( $dashboard->dashboard_tabs() as $key => $value ): ?>

		<div class="atbd_tab_inner <?php echo ( $counter == 1 ) ? 'tabContentActive' : ''; ?>" id="<?php echo esc_attr( $key ); ?>"><?php echo $value['content']; ?></div>

		<?php do_action( 'directorist_dashboard_contents', $key, $dashboard ); ?>
		
		<?php $counter++; ?>

	<?php endforeach; ?>

	<?php do_action( 'directorist_after_dashboard_contents', $dashboard ); ?>

</div>