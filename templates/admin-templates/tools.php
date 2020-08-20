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
$steps = isset($_GET['step']) ? sanitize_key($_GET['step']) : '';
?>
<div class="csv-action-btns">
	<a class="<?php echo 'csv_import' == $current_tab ? 'btn-active' : ''; ?>" href="<?php echo esc_url($url); ?>"><span class="dashicons dashicons-download"></span> <?php _ex('Import', 'admin csv', 'directorist'); ?></a>
	<a class="<?php echo 'csv_export' == $current_tab ? 'btn-active' : ''; ?>" href="<?php echo esc_url(add_query_arg('tab', 'csv_export')); ?>"><span class="dashicons dashicons-upload"></span> <?php _ex('Export', 'admin csv', 'directorist'); ?></a>
<?php if ('csv_import' == $current_tab) { ?>
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
<?php }
	if ('csv_import' == $current_tab) {
		if (!$steps) {
			ATBDP()->load_template('admin-templates/import-export/step-one');
		} elseif ('2' == $steps) {
			ATBDP()->load_template('admin-templates/import-export/step-two', $args);
		} elseif ('3' == $steps) {
			ATBDP()->load_template('admin-templates/import-export/step-done');
		}
	}
	if ('csv_export' == $current_tab) {
		ATBDP()->load_template('admin-templates/import-export/export');
	} ?>