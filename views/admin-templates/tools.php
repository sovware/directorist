<?php

/**
 * Admin View: listing import form
 *
 * @package directorist/Admin
 */

if (!defined('ABSPATH')) {
	exit;
}
$url = admin_url() . 'edit.php?post_type=at_biz_dir&page=tools&tab=csv_import';
$steps = isset($_GET['step']) ? sanitize_key($_GET['step']) : '';
$download_link = ATBDP_URL .'views/admin-templates/import-export/data/dummy.csv';
?>
<div class="csv-action-btns">
	<div class="csv-center csv-export">
		<input type="button" value="<?php esc_attr_e( 'Download a sample CSV', 'directorist' ); ?>" class="button-secondary" name="atbdp_ie_download_sample" id="atbdp_ie_download_sample" data-sample-csv="<?php echo $download_link; ?>">
	</div>
</div>
<div class="csv-action-steps">
	<ul>
		<li class="<?php echo !$steps ? esc_attr('active') : ($steps > 1 ? esc_attr('done') : ''); ?>">
			<span class="step"><span class="step-count">1</span> <span class="dashicons dashicons-yes"></span></span>
			<span class="step-text"><?php _e('Upload CSV File', 'directorist'); ?></span>
		</li>
		<li class="atbdp-mapping-step <?php echo ('2' == $steps) ? esc_attr('active') : ($steps > 2 ? esc_attr('done') : ''); ?>">
			<span class="step"><span class="step-count">2</span> <span class="dashicons dashicons-yes"></span></span>
			<span class="step-text"><?php _e('Column Mapping', 'directorist'); ?></span>
		</li>
		<li class="atbdp-progress-step <?php echo  ($steps == 3) ? esc_attr('done') : ''; ?>">
			<span class="step"><span class="step-count">3</span> <span class="dashicons dashicons-yes"></span></span>
			<span class="step-text"><?php _e('Import', 'directorist'); ?></span>
		</li>
		<li class="<?php echo ('3' == $steps) ? esc_attr('active done') : ''; ?>">
			<span class="step"><span class="step-count">4</span> <span class="dashicons dashicons-yes"></span></span>
			<span class="step-text"><?php _e('Done', 'directorist'); ?></span>
		</li>
	</ul>
</div>
<?php 
	if (!$steps) {
		ATBDP()->load_template('admin-templates/import-export/step-one');
	} elseif ('2' == $steps) {
		ATBDP()->load_template('admin-templates/import-export/step-two', $args);
	} elseif ('3' == $steps) {
		ATBDP()->load_template('admin-templates/import-export/step-done');
	}
?>