<?php

/**
 * CSV mapping
 *
 * @package Directorist
 */

if (!defined('ABSPATH')) {
	exit;
}
$file            = isset( $_GET['file'] ) ? wp_unslash( $_GET['file'] ) : '';
$delimiter       = isset( $_GET['delimiter'] ) ? wp_unslash( $_GET['delimiter'] ) : ',';
$posts           = csv_get_data( $file, true, $delimiter );
$total           = count( $posts );
$update_existing = isset( $_GET['update_existing'] ) ? sanitize_key( $_GET['update_existing'] ) : false;

$builder_posts    = csv_get_data( $file, true, ',' );
$csv_from_builder = csv_from_builder( $builder_posts );
$delimiter        = ( $csv_from_builder ) ? ',' : $delimiter;
$total            = ( $csv_from_builder ) ? count( $builder_posts ) : $total;

// var_dump( [
// 	// 'builder_posts' => $builder_posts,
// 	'file' => $file, 
// 	'csv_from_builder' => $csv_from_builder
// ] );

// csv_from_builder
function csv_from_builder( $data = [] ) {
	if ( 'array' !== gettype( $data ) ) { return false; }
	if ( ! count( $data ) ) { return false; }

	if (  empty( $data[0]['directory_type'] ) ) {
		return false;
	}

	return true;
}

?>
<div class="csv-wrapper">
	<div class="csv-center csv-fields">
		<form class="atbdp-progress-form-content directorist-importer" id="atbdp_csv_step_two" method="post">
			<header>
				<?php if ( $csv_from_builder ) : ?>
					<h2><?php esc_html_e('Importing listings', 'directorist'); ?></h2>
				<?php else: ?>
					<h2><?php esc_html_e('Map CSV fields to listings', 'directorist'); ?></h2>
					<p><?php esc_html_e('Select Directorist fields to map it against your CSV file fields, leave it as "Do not import" to skip certain fields.', 'directorist'); ?></p>
				<?php endif; ?>
			</header>

			<div class="form-content">
				<section class="atbdp-importer-mapping-table-wrapper">
					<h3><?php printf(__('Total %s items selected ', 'directorist'), $total); ?></h3>
					<div class="directory_type_wrapper">
						<?php if ( $csv_from_builder ) :
							?><input type="hidden" name="csv_file" value="<?php echo $file ?>"><?php
							foreach ( $builder_posts[0] as $post_key => $post_value  ) {
								?><input type="hidden" class="atbdp_map_to" name="<?php echo $post_key ?>" value="<?php echo $post_key ?>"><?php
							}
						else:
							if( count( directory_types() ) > 1 ) { ?>
								<label for="directory_type"><?php esc_html_e('Select Directory', 'directorist'); ?></label>
								<select name="directory_type" id="directory_type">
									<option value="">--Select--</option>
									<?php
									foreach( directory_types() as $term ) {
										$default = get_term_meta( $term->term_id, '_default', true ); ?>
											<option <?php echo !empty( $default ) ? 'selected' : ''; ?> value="<?php echo esc_attr( $term->term_id); ?>"><?php echo esc_attr( $term->name ); ?></option>
									<?php } ?>
								</select>
							<?php }
							$this->tools->get_data_table();
						endif; ?>
					</div>
				</section>
			</div>
			<div class="atbdp-actions">
				<button type="submit" class="button btn-run-importer" value="<?php esc_attr_e('Run the importer', 'directorist'); ?>" name="save_step_two"><?php esc_html_e('Run the importer', 'directorist'); ?></button>
				<input type="hidden" name="csv_file" value="<?php echo esc_attr($file); ?>">
				<input type="hidden" name="delimiter" value="<?php echo esc_attr($delimiter); ?>" />
				<input type="hidden" name="update_existing" value="<?php echo $update_existing; ?>" />
				<?php wp_nonce_field('directorist-csv-importer'); ?>
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
				<span class="spinner is-active"></span>
				<h2><?php esc_html_e( 'Importing', 'directorist' );
					?></h2>
				<p><?php esc_html_e( 'Your listing are now being imported...', 'directorist' );
					?></p>
			</header>
			<section>
				<span class="importer-notice"><?php esc_html_e('Please don\'t reload the page', 'directorist')?></span>
				<div class="directorist-importer-wrapper">
					<progress class="directorist-importer-progress" max="100" value="0"></progress>
					<span class="directorist-importer-length"></span>
				</div>
				<span class="importer-details"></span>
			</section>
		</div>
	</div>
</div>