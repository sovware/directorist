<?php

/**
 * CSV mapping
 *
 * @package Directorist
 */

defined( 'ABSPATH' ) || exit;

$file_id       = isset( $_GET['file_id'] ) ? absint( $_GET['file_id'] ) : 0;
$delimiter     = isset( $_GET['delimiter'] ) ? sanitize_text_field( wp_unslash( $_GET['delimiter'] ) ) : ',';
$csv_file      = get_attached_file( $file_id );
$csv_file      = $args['controller']->validate_csv_file( $csv_file );
$total         = 0;
$is_valid_file = false;

if ( ! is_wp_error($csv_file ) ) {
	$total         = $args['controller']->get_importer( $csv_file, $delimiter )->get_total_items();
	$directories   = directory_types();
	$is_valid_file = true;
}
?>
<div class="csv-wrapper">
	<div class="csv-center csv-fields">
		<form data-total="<?php echo esc_attr( $total ); ?>" class="atbdp-progress-form-content directorist-importer" id="atbdp_csv_step_two" method="post">
			<header>
				<h2><?php esc_html_e('Map CSV Columns → Listing Fields', 'directorist'); ?></h2>
				<p><?php esc_html_e('Select Directorist fields to map it against your CSV file columns, leave it as "Do not import" to skip certain fields.', 'directorist'); ?></p>
			</header>

			<div class="form-content">
				<section class="atbdp-importer-mapping-table-wrapper">
					<?php if ( $is_valid_file ) : ?>
						<h3><?php printf( esc_html__( 'Found %s listings.', 'directorist' ), $total ); ?></h3>
						<div class="directory_type_wrapper">
							<?php if ( count( $directories ) > 1 ) : ?>
								<label for="directory_type"><?php esc_html_e( 'Select Directory', 'directorist' ); ?></label>
								<select class="directorist_directory_type_in_import" id="directory_type">
									<option value="">--Select--</option>
									<?php
									foreach ( $directories as $directory_term ) {
										$default = get_term_meta( $directory_term->term_id, '_default', true );
										printf(
											'<option %s value="%s">%s</option>',
											empty( $default ) ? '' : 'selected',
											esc_attr( $directory_term->term_id ),
											esc_html( $directory_term->name )
										);
									} ?>
								</select>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php
					if ( $is_valid_file ) {
						$args['controller']->render_field_map_table( $csv_file, $delimiter );
					} else {
						printf(
							'<p style="font-style:italic; color: #d63638">%s</p>',
							$csv_file->get_error_message(),
						);
					}
					?>
				</section>
			</div>

			<div class="atbdp-actions">
				<button type="submit" class="button btn-run-importer" value="<?php esc_attr_e('Import Listings', 'directorist'); ?>" name="save_step_two"><?php esc_html_e('Import Listings', 'directorist'); ?></button>
				<input type="hidden" class="directorist-listings-importer-config-field" name="csv_file" value="<?php echo esc_attr( $file_id ); ?>">
				<input type="hidden" class="directorist-listings-importer-config-field" name="delimiter" value="<?php echo esc_attr( $delimiter ); ?>" />
				<?php wp_nonce_field('directorist-csv-importer' ); ?>
			</div>
		</form>
		<div id="directorist-type-preloader">
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>
	</div>
	<div class="csv-center">
		<div class="directorist-importer__importing" style="display: none;">
			<header>
				<h2><?php esc_html_e( 'Importing', 'directorist' ); ?></h2>
				<p><?php esc_html_e( 'Your listings are now being imported...', 'directorist' ); ?></p>
				<span class="spinner is-active"></span>
			</header>
			<section>
				<div class="directorist-importer-wrapper">
					<progress class="directorist-importer-progress" max="100" value="0"></progress>
					<span class="directorist-importer-length"></span>
				</div>
				<div class="importer-progress-notice">
					<span class="importer-notice"><?php esc_html_e("Please don't reload the page.", 'directorist' ); ?></span>
					<span class="importer-details"></span>
				</div>
			</section>
		</div>
	</div>
</div>
