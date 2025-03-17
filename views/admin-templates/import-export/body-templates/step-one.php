<?php
/**
 * @package Directorist
 */

defined( 'ABSPATH' ) || exit;

$bytes      = apply_filters('import_upload_size_limit', wp_max_upload_size());
$size       = size_format($bytes);
$upload_dir = wp_upload_dir();

?>
<div class="csv-wrapper">
	<div class="csv-center">
		<form class="atbdp-progress-form-content directorist-importer" id="atbdp_csv_step_one" enctype="multipart/form-data" method="post">
			<header>
				<h2><?php esc_html_e( 'Upload CSV File', 'directorist' ); ?></h2>
				<p><?php
					/* translators: %1$s - link opening, %2$s - link closing */
					printf(
						esc_html__( 'This tool enables you to import listings from a CSV file into your directory. We strongly advise you to read our CSV import %1$s documentation %2$s before using the tool to ensure proper usage.', 'directorist' ),
						'<a target="_blank" href="https://directorist.com/documentation/directorist/gettings-started/csv-import/">',
						'</a>'
					);
					?></p>
			</header>
			<div class="form-content">
				<section>
					<div class="form-table directorist-importer-options">
						<!-- <h4 for="upload"><?php esc_html_e( 'Select a CSV file from your computer', 'directorist' ); ?></h4> -->
						<div>
							<?php
							if ( ! empty( $upload_dir['error'] ) ) {
								?>
								<div class="inline error">
									<p><?php esc_html_e( 'Please fix the following error before uploading the CSV file:', 'directorist' ); ?></p>
									<p><strong><?php echo esc_html( $upload_dir['error'] ); ?></strong></p>
								</div>
								<?php
							} else {
								?>
								<div class="csv-upload">
									<input type="file" id="upload" name="import" required size="25" accept=".csv,.txt" />
									<label for="upload"><span class="upload-btn"><i class="dashicons dashicons-upload"></i><?php esc_html_e( 'Select a CSV File', 'directorist' ); ?></span> <span class="file-name"></span></label>
									<small><?php
										printf(
											/* translators: %s: maximum upload size */
											esc_html__( 'Maximum upload file size: %s', 'directorist' ),
											esc_html( $size )
										);
									?></small>
								</div>
								<input type="hidden" name="action" value="save" />
								<input type="hidden" name="max_file_size" value="<?php echo esc_attr( $bytes ); ?>" />
								<?php
							}
							?>
						</div>
						<div class="csv-delimiter">
							<label><?php esc_html_e( 'CSV Delimiter', 'directorist' ); ?></label>
							<input type="text" name="delimiter" placeholder="," size="2" />
						</div>
					</div>
				</section>
			</div>
			<div class="atbdp-actions">
				<button type="submit" class="button" value="<?php esc_attr_e( 'Upload CSV', 'directorist' ); ?>" name="directorist_upload_csv"><?php esc_html_e( 'Upload CSV', 'directorist' ); ?></button>
				<?php wp_nonce_field( 'directorist_importer_upload_csv' ); ?>
			</div>
		</form>
	</div>
</div>