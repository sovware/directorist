<?php
/**
 * Admin View: listing import form
 *
 * @package directorist/Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wp;
$current_url = home_url(add_query_arg(array(), $wp->request));
?>
<div class="wrap woocommerce">
		<form method="post" id="mainform" action="" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
            <a href="<?php esc_url( $current_url . '&tab="import"' ); ?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'Import', 'directorist' ); ?></a>
            <a href="https://rafiq.directorist.com/wp-admin/admin.php?page=wc-settings&amp;tab=shipping" class="nav-tab "><?php esc_html_e( 'Export', 'directorist' ); ?></a>		
        </nav>
</div>
<form class="atbdp-progress-form-content directorist-importer" enctype="multipart/form-data" method="post">
	<header>
		<h2><?php esc_html_e( 'Import listings from a CSV file', 'directorist' ); ?></h2>
		<p><?php esc_html_e( 'This tool allows you to import (or merge) listing data to your store from a CSV file.', 'directorist' ); ?></p>
	</header>
	<section>
		<table class="form-table directorist-importer-options">
			<tbody>
				<tr>
					<th scope="row">
						<label for="upload">
							<?php esc_html_e( 'Choose a CSV file from your computer:', 'directorist' ); ?>
						</label>
					</th>
					<td>
						<?php
						if ( ! empty( $upload_dir['error'] ) ) {
							?>
							<div class="inline error">
								<p><?php esc_html_e( 'Before you can upload your import file, you will need to fix the following error:', 'directorist' ); ?></p>
								<p><strong><?php echo esc_html( $upload_dir['error'] ); ?></strong></p>
							</div>
							<?php
						} else {
							?>
							<input type="file" id="upload" name="import" size="25" />
							<input type="hidden" name="action" value="save" />
							<input type="hidden" name="max_file_size" value="<?php echo esc_attr( $bytes ); ?>" />
							<br>
							<small>
								<?php
								printf(
									/* translators: %s: maximum upload size */
									esc_html__( 'Maximum size: %s', 'directorist' ),
									esc_html( $size )
								);
								?>
							</small>
							<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<th><label for="directorist-importer-update-existing"><?php esc_html_e( 'Update existing listings', 'directorist' ); ?></label><br/></th>
					<td>
						<input type="hidden" name="update_existing" value="0" />
						<input type="checkbox" id="directorist-importer-update-existing" name="update_existing" value="1" />
						<label for="directorist-importer-update-existing"><?php esc_html_e( 'Existing listings that match by ID or SKU will be updated. listings that do not exist will be skipped.', 'directorist' ); ?></label>
					</td>
				</tr>
			</tbody>
		</table>
	</section>
	<div class="atbdp-actions">
		<button type="submit" class="button button-primary button-next" value="<?php esc_attr_e( 'Continue', 'directorist' ); ?>" name="save_step"><?php esc_html_e( 'Continue', 'directorist' ); ?></button>
		<?php wp_nonce_field( 'directorist-csv-importer' ); ?>
	</div>
</form>
