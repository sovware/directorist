<?php
/**
 * CSV mapping
 *
 * @package Directorist
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form class="wc-progress-form-content directorist-importer" method="post">
	<header>
		<h1><?php esc_html_e( 'Step Two', 'directorist' ); ?></h1>
		<h2><?php esc_html_e( 'Map CSV fields to listings', 'directorist' ); ?></h2>
		<p><?php esc_html_e( 'Select fields from your CSV file to map against listings fields, or to ignore during import.', 'directorist' ); ?></p>
	</header>
	<section class="wc-importer-mapping-table-wrapper">
		<table class="widefat wc-importer-mapping-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Column name', 'directorist' ); ?></th>
					<th><?php esc_html_e( 'Map to field', 'directorist' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$headers = $args['data'];
				$fields = $args['fields'];
				foreach ( $headers as $index => $name ) : ?>
					<tr>
					<td class="wc-importer-mapping-table-name">
							<p><?php echo esc_html( $index ); ?></p>
							<?php if ( ! empty( $name ) ) : ?>
								<span class="description"><?php esc_html_e( 'Sample:', 'directorist' ); ?> <code><?php echo esc_html( $name ); ?></code></span>
							<?php endif; ?>
						</td>
						<td class="wc-importer-mapping-table-field">
							<input type="hidden" name="map_from[<?php echo esc_attr( $index ); ?>]" value="<?php echo esc_attr( $name ); ?>" />
							<select name="map_to[<?php echo esc_attr( $index ); ?>]">
								<option value=""><?php esc_html_e( 'Do not import', 'woocommerce' ); ?></option>
								<option value="">--------------</option>
								<?php foreach ( $fields as $key => $value ) : ?>
												<option value="<?php echo esc_attr( $key ); ?>" <?php // selected( $mapped_value, $key ); ?>><?php echo esc_html( $value ); ?></option>
								<?php endforeach ?>
							</select>
						</td>
					</tr>



				<?php



				endforeach;
				?>
			</tbody>
		</table>
	</section>
	<div class="wc-actions">
		<button type="submit" class="button button-primary button-next" value="<?php esc_attr_e( 'Run the importer', 'directorist' ); ?>" name="save_step"><?php esc_html_e( 'Run the importer', 'directorist' ); ?></button>
		<input type="hidden" name="file" value="<?php echo esc_attr( $this->file ); ?>" />
		<input type="hidden" name="delimiter" value="<?php echo esc_attr( $this->delimiter ); ?>" />
		<input type="hidden" name="update_existing" value="<?php echo (int) $this->update_existing; ?>" />
		<?php wp_nonce_field( 'directorist-csv-importer' ); ?>
	</div>
</form>
