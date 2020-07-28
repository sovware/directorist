<?php
/**
 * @package Directorist
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$download_link = ATBDP_URL .'templates/import-export/data/dummy.csv';
?>
<div class="csv-wrapper">
	<div class="csv-center csv-export">
		<input type="button" value="<?php esc_attr_e( 'Download a sample CSV', 'directorist' ); ?>" class="button-secondary" name="atbdp_ie_download_sample" id="atbdp_ie_download_sample" data-sample-csv="<?php echo $download_link; ?>">
	</div>
</div>