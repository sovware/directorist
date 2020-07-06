<?php
/**
 * Admin View: Importer - Done!
 *
 * @package WooCommerce\Admin\Importers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="csv-wrapper">
	<div class="csv-center csv-import-done">
		<div class="wc-progress-form-content directorist-importer">
			<div class="wc-actions">
				<span class="dashicons dashicons-yes"></span>
				<p class="import-complete"><?php esc_html_e( 'Import Completed!', 'directorist' ); ?></p>
				<p><strong>350</strong> products imported. <strong>40</strong> products were skipped</p>
			</div>
		</div>
		<div class="atbdp-actions">
			<a class="button" href="<?php echo esc_url( admin_url( 'edit.php?post_type=at_biz_dir' ) ); ?>"><?php esc_html_e( 'View listings', 'directorist' ); ?></a>
		</div>
	</div>
</div>

