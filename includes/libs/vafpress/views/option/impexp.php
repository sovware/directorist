<div class="vp-field vp-textarea" data-vp-type="vp-textarea">
	<div class="label">
		<label>
			<?php _e('Import', ATBDP_TEXTDOMAIN) ?>
		</label>
		<div class="description">
			<p><?php _e('Import Settings Data', ATBDP_TEXTDOMAIN) ?></p>
		</div>
	</div>
	<div class="field">
		<div class="input">
			<textarea id="vp-js-import_text"></textarea>
			<div class="buttons">
				<input id="vp-js-import" class="vp-button button" type="button" value="<?php _e('Import', ATBDP_TEXTDOMAIN) ?>" />
				<span style="margin-left: 10px;">
					<span id="vp-js-import-loader" class="vp-field-loader" style="display: none;"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>" style="vertical-align: middle;"></span>
					<span id="vp-js-import-status" style="display: none;"></span>
				</span>
			</div>
		</div>
	</div>
</div>

<div class="vp-field vp-textarea" data-vp-type="vp-textarea">
	<div class="label">
		<label>
			<?php _e('Export', ATBDP_TEXTDOMAIN) ?>
		</label>
		<div class="description">
			<p><?php _e('Export Settings Data', ATBDP_TEXTDOMAIN) ?></p>
		</div>
	</div>
	<div class="field">
		<div class="input">
			<textarea id="vp-js-export_text" onclick="this.focus();this.select()" readonly="readonly"></textarea>
			<div class="buttons">
				<input id="vp-js-export" class="vp-button button" type="button" value="<?php _e('Export', ATBDP_TEXTDOMAIN) ?>" />
				<span style="margin-left: 10px;">
					<span id="vp-js-export-loader" class="vp-field-loader" style="display: none;"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>" style="vertical-align: middle;"></span>
					<span id="vp-js-export-status" style="display: none;"></span>
				</span>
			</div>
		</div>
	</div>
</div>