<?php
/**
 * @package Directorist
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$bytes      = apply_filters('import_upload_size_limit', wp_max_upload_size());
$size       = size_format($bytes);
$upload_dir = wp_upload_dir();

?>
<div class="csv-wrapper">
	<div class="csv-center">
		<form class="atbdp-progress-form-content directorist-importer" id="atbdp_csv_step_one" enctype="multipart/form-data" method="post">
			<header>
				<h2><?php esc_html_e( 'Select CSV File', 'directorist' ); ?></h2>
			<p>
				<?php esc_html_e( 'This tool allows you to import listing data to your directory from a CSV file.', 'directorist' ); ?>
				<?php esc_html_e( 'We strongly recommend reading our CSV import ', 'directorist' ); ?>
				<a target="_blank" href="https://directorist.com/documentation/directorist/gettings-started/csv-import/"><?php esc_html_e( 'documentation', 'directorist' ); ?></a>
				<?php esc_html_e( ' first to help you do things in the right way.', 'directorist' ); ?>
			</p>
			</header>
			<div class="form-content">
				<section>
					<div class="form-table directorist-importer-options">
						<h4 for="upload">
							<?php esc_html_e( 'Choose a CSV file from your computer:', 'directorist' ); ?>
						</h4>
						<div>
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
								<div class="csv-upload">
									<input type="file" id="upload" name="import" required size="25" />
									<label for="upload"><span class="upload-btn"><i class="dashicons dashicons-upload"></i> <?php esc_html_e( 'Upload CSV', 'directorist' ); ?></span> <span class="file-name"><?php esc_html_e( 'No file chosen', 'directorist' ); ?></span></label>
									<small>
										<?php
										printf(
											/* translators: %s: maximum upload size */
											esc_html__( 'Maximum size: %s', 'directorist' ),
											esc_html( $size )
										);
										?>
									</small>
								</div>
								<input type="hidden" name="action" value="save" />
								<input type="hidden" name="max_file_size" value="<?php echo esc_attr( $bytes ); ?>" />
								<?php
							}
							?>
						</div>
						<!-- <div class="update-existing">
							<div>
								<label for="directorist-importer-update-existing" class="ue">
									<?php esc_html_e( 'Update existing listings', 'directorist' ); ?>
								</label>
								<input type="hidden" name="update_existing" value="0" />
								<input type="checkbox" id="directorist-importer-update-existing" name="update_existing" value="1" />
								<label for="directorist-importer-update-existing"><?php esc_html_e( 'Existing listings that match by ID will be updated. listings that do not exist will be skipped.', 'directorist' ); ?></label>
							</div>
						</div> -->
						<div class="csv-delimiter">
							<label><?php esc_html_e( 'CSV Delimiter', 'directorist' ); ?></label>
							<input type="text" name="delimiter" placeholder="," size="2" />
						</div>
					</div>
				</section>
			</div>
			<div class="atbdp-actions">
				<button type="submit" class="button" value="<?php esc_attr_e( 'Continue', 'directorist' ); ?>" name="atbdp_save_csv_step"><?php esc_html_e( 'Continue', 'directorist' ); ?></button>
				<?php wp_nonce_field( 'directorist-csv-importer' ); ?>
			</div>
		</form>
	</div>
</div>