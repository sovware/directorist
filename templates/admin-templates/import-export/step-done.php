<?php
/**
 * Admin View: Importer - Done!
 *
 * @package WooCommerce\Admin\Importers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$imported = isset($_GET['listing-imported']) ? sanitize_text_field($_GET['listing-imported']) : '';
$failed = isset($_GET['listing-failed']) ? sanitize_text_field($_GET['listing-failed']) : '0';
?>
<div class="csv-wrapper">
	<div class="csv-center csv-import-done">
		<div class="wc-progress-form-content directorist-importer">
			<div class="wc-actions">
				<span class="dashicons dashicons-yes"></span>
				<p class="import-complete"><?php esc_html_e( 'Import Completed!', 'directorist' ); ?></p>
				<p><strong><?php echo esc_attr($imported)?></strong> <?php _e('listings imported', 'directorist');
				if(($failed != 'NaN') && ($failed != '0')){
				?> 
				&<strong><?php echo ' ' .esc_attr($failed);?></strong> <?php _e('were skipped.', 'directorist');
				}
				?>
			</p>
			</div>
		</div>
		<div class="atbdp-actions">
			<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=at_biz_dir' ) ); ?>"><?php esc_html_e( 'View listings', 'directorist' ); ?></a>
		</div>
	</div>
</div>

