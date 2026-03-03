/**
 * TinyMCE editor handlers for conditional logic
 */
import { WIDGET_KEY_TO_FIELD_KEY } from '../field-mapping.js';

/**
 * @param {jQuery} $
 * @param {Function} triggerFn - (fieldName, fieldKey, $changedField) => void
 */
export function setupTinyMCEHandlers($, triggerFn) {
	if (typeof tinymce === 'undefined') return;

	function attachTinyMCEEvents(editor) {
		if (!editor || !editor.id) return;

		const editorId = editor.id;
		const $editorTextarea = $('#' + editorId);
		if (!$editorTextarea.length) return;

		const $formGroup = $editorTextarea.closest('.directorist-form-group');
		const isWordPressContentEditor =
			editorId === 'content' &&
			$editorTextarea.closest('#postdivrich, #wp-content-wrap').length;
		if (!$formGroup.length && !isWordPressContentEditor) return;

		const fieldName = $editorTextarea.attr('name') || editorId;
		const fieldKey = WIDGET_KEY_TO_FIELD_KEY[fieldName] || fieldName;

		editor.off('input keyup change NodeChange');
		editor.on('input keyup change NodeChange', function () {
			triggerFn(fieldName, fieldKey, $editorTextarea);
		});
	}

	$(document).ready(function () {
		try {
			if (typeof tinymce.on === 'function') {
				tinymce.on('AddEditor', function (e) {
					if (e && e.editor) attachTinyMCEEvents(e.editor);
				});
			}
		} catch (e) {}

		function initExistingEditors() {
			try {
				if (
					typeof tinymce !== 'undefined' &&
					tinymce.editors &&
					typeof tinymce.editors.forEach === 'function'
				) {
					tinymce.editors.forEach(function (editor) {
						if (editor) attachTinyMCEEvents(editor);
					});
				}
			} catch (e) {}
		}
		initExistingEditors();
		setTimeout(initExistingEditors, 500);
		setTimeout(initExistingEditors, 1000);
		setTimeout(initExistingEditors, 2000);
	});

	$(document).on('tinymce-editor-init', function (e, editor) {
		if (editor) attachTinyMCEEvents(editor);
	});
}
