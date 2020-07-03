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
<div class="wc-progress-form-content directorist-importer">
	<div class="wc-actions">
        <p><?php esc_html_e( 'Import Completed!', 'directorist' ); ?></p>
		<a class="button button-primary" href="<?php echo esc_url( admin_url( 'edit.php?post_type=at_biz_dir' ) ); ?>"><?php esc_html_e( 'View listings', 'directorist' ); ?></a>
	</div>
</div>
