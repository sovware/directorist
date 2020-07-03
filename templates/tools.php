<?php

/**
 * Admin View: listing import form
 *
 * @package directorist/Admin
 */

if (!defined('ABSPATH')) {
	exit;
}
$tabs = array('csv_import', 'csv_export');

if (!empty($_GET['tab'])) {
	$current_tab = $_GET['tab'];
} else {
	$current_tab = 'csv_import';
}
$url = admin_url() . 'edit.php?post_type=at_biz_dir&page=tools&tab=csv_import';
?>
<div class="csv-action-btns">
	<a class="<?php echo 'csv_import' == $current_tab ? 'btn-active' : ''; ?>" href="<?php echo esc_url($url); ?>"><span class="dashicons dashicons-download"></span> <?php _ex('Import', 'admin csv', 'directorist'); ?></a>
	<a class="<?php echo 'csv_export' == $current_tab ? 'btn-active' : ''; ?>" href="<?php echo esc_url(add_query_arg('tab', 'csv_export')); ?>"><span class="dashicons dashicons-upload"></span> <?php _ex('Export', 'admin csv', 'directorist'); ?></a>
</div>

<div class="csv-action-steps">
	<ul>
		<li class="active">
			<span class="step">1</span>
			<span>Upload CSV File</span>
		</li>
		<li>
			<span class="step">2</span>
			<span>Column Mapping</span>
		</li>
		<li>
			<span class="step">3</span>
			<span>Import</span>
		</li>
		<li>
			<span class="step">4</span>
			<span>Done</span>
		</li>
	</ul>
</div>

<?php if ('csv_import' == $current_tab) { ?>

<?php
	$steps = isset($_GET['step']) ? sanitize_key($_GET['step']) : '';
	if (!$steps) {
		ATBDP()->load_template('import-export/step-one');
	} elseif ('2' == $steps) {
		ATBDP()->load_template('import-export/step-two', $args);
	} elseif ('3' == $steps) {
		ATBDP()->load_template('import-export/step-three');
	}
}
if ('csv_export' == $current_tab) {
	ATBDP()->load_template('import-export/export');
} ?>