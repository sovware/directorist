/**
 * Extract field values from form elements
 */
import {
	escapeCssId,
	isTaxonomySelectField,
	mapFieldKeyToSelector,
	SELECTORS,
	WIDGET_KEY_TO_FIELD_KEY,
} from './field-mapping.js';
import {
	getLabelsFromSelect2Container,
	parseIdsString,
	parseLabelsString,
} from './helpers.js';

/**
 * Get field value from form by field key.
 * @param {string} fieldKey - Field key (e.g. 'category', 'custom-select')
 * @param {jQuery} $ - jQuery
 * @returns {*} Value, array, 'uploaded', or null
 */
export function getFieldValue(fieldKey, $) {
	// Special handling for privacy_policy field (checkbox field)
	if (fieldKey === 'privacy_policy') {
		const $privacyCheckbox = $(
			'input[name="privacy_policy"], #directorist_submit_privacy_policy'
		);
		if ($privacyCheckbox.length) {
			return $privacyCheckbox.is(':checked') ? 'checked' : '';
		}
		return '';
	}

	// Special handling for listing_img field (image upload field)
	if (fieldKey === 'listing_img' || fieldKey === 'image_upload') {
		const $imageUploadWrapper = $('.directorist-form-image-upload-field');
		if ($imageUploadWrapper.length) {
			const $previewSection = $imageUploadWrapper.find(
				'.ezmu__preview-section.ezmu--show'
			);
			if ($previewSection.length > 0) {
				return 'uploaded';
			}
		}
		return null;
	}

	let $field = null;

	// Handle category, tag, and location fields
	if (
		fieldKey === 'category' ||
		fieldKey === 'categories' ||
		fieldKey === 'admin_category_select[]' ||
		fieldKey === 'in_cat'
	) {
		$field = $(SELECTORS.CATEGORY_SELECT).first();
		if (!$field.length) {
			const $checkboxes = $(SELECTORS.CATEGORY_CHECKLIST_CHECKED);
			if ($checkboxes.length) {
				return $checkboxes
					.map(function () {
						return $(this).val();
					})
					.get();
			}
			return [];
		}
	} else if (
		fieldKey === 'tag' ||
		fieldKey === 'tags' ||
		fieldKey === 'tax_input[at_biz_dir-tags][]' ||
		fieldKey === 'in_tag[]'
	) {
		$field = $(SELECTORS.TAGS_SELECT).first();
		if (!$field.length) {
			const $checkboxes = $(SELECTORS.TAGS_CHECKLIST_CHECKED);
			if ($checkboxes.length) {
				return $checkboxes
					.map(function () {
						return $(this).val();
					})
					.get();
			}
			return [];
		}
		if ($field.is('div') && !$field.is('select')) {
			const $tagItems = $(SELECTORS.TAG_CHECKLIST_ITEMS);
			if ($tagItems.length) {
				return $tagItems
					.map(function () {
						return $(this)
							.clone()
							.children()
							.remove()
							.end()
							.text()
							.trim();
					})
					.get()
					.filter(Boolean);
			}
			const $textarea = $(SELECTORS.TAG_TEXTAREA);
			if ($textarea.length && $textarea.val()) {
				const raw = String($textarea.val()).trim();
				if (raw) {
					return raw
						.split(/[,\u00A0]+/)
						.map((s) => s.trim())
						.filter(Boolean);
				}
			}
			return [];
		}
	} else if (
		fieldKey === 'location' ||
		fieldKey === 'locations' ||
		fieldKey === 'tax_input[at_biz_dir-location][]' ||
		fieldKey === 'in_loc' ||
		fieldKey === 'address'
	) {
		$field = $(SELECTORS.LOCATION_SELECT).first();
		if (!$field.length) {
			const $addressInput = $(SELECTORS.SEARCH_ADDRESS);
			if ($addressInput.length) {
				const val = $addressInput.val();
				return val && val.trim() ? [val.trim()] : [];
			}
			const $checkboxes = $(SELECTORS.LOCATION_CHECKLIST_CHECKED);
			if ($checkboxes.length) {
				return $checkboxes
					.map(function () {
						return $(this).val();
					})
					.get();
			}
			return [];
		}
	}

	// Search form: in_tag[] checkboxes (1 value per selected = label or ID)
	if (
		(fieldKey === 'in_tag[]' ||
			fieldKey === 'tag' ||
			fieldKey === 'tags') &&
		$field &&
		$field.is(SELECTORS.IN_TAG)
	) {
		const $checkboxes = $(`${SELECTORS.IN_TAG}:checked`);
		if ($checkboxes.length) {
			const values = [];
			$checkboxes.each(function () {
				const $cb = $(this);
				let $label = $cb.siblings('label').first();
				if (!$label.length && $cb.attr('id')) {
					$label = $(`label[for="${$cb.attr('id')}"]`);
				}
				const label = $label.length ? $label.text().trim() : '';
				if (label) {
					values.push(label);
				} else {
					const id = $cb.val();
					if (id) values.push(String(id));
				}
			});
			return values;
		}
		return [];
	}

	const isTaxonomyField = isTaxonomySelectField($field);

	if (isTaxonomyField) {
		// Strategy 1: data-selected-label AND data-selected-id
		const cachedLabels = $field.attr('data-selected-label');
		const cachedIds = $field.attr('data-selected-id');
		const isTagField = $field.is(SELECTORS.TAGS);

		if (cachedLabels && cachedLabels.trim()) {
			const parsedLabels = parseLabelsString(cachedLabels);
			const parsedIds = cachedIds ? parseIdsString(cachedIds) : [];
			const combined = [];

			if (isTagField) {
				parsedLabels.forEach((label) => {
					if (label) combined.push(label);
				});
				parsedIds.forEach((id) => {
					if (id && !parsedLabels.includes(id)) {
						combined.push(id);
					}
				});
			} else {
				// Category/location: return IDs only so "is" = strict match (not "contains")
				parsedIds.forEach((id) => {
					if (id) combined.push(id);
				});
			}

			if (combined.length > 0) {
				return combined;
			}
		}

		// Strategy 2: Select2 API
		if (
			$field.hasClass('select2-hidden-accessible') &&
			typeof $field.select2 === 'function'
		) {
			try {
				const selectedData = $field.select2('data');
				if (selectedData && selectedData.length > 0) {
					const combined = [];
					const isTagField = $field.is(SELECTORS.TAGS);

					selectedData.forEach((item) => {
						if (isTagField) {
							if (item.text) combined.push(item.text);
							if (item.id && item.id !== item.text) {
								combined.push(String(item.id));
							}
						} else {
							// Category/location: IDs only for strict "is" match
							if (item.id) combined.push(String(item.id));
						}
					});
					if (combined.length > 0) {
						$field.attr(
							'data-selected-label',
							selectedData
								.map((item) => item.text || '')
								.filter((t) => t)
								.join(',')
						);
						$field.attr(
							'data-selected-id',
							selectedData
								.map((item) => item.id || '')
								.filter((id) => id)
								.join(',')
						);
						return combined;
					}
				}
			} catch (e) {
				// Select2 might not be initialized yet
			}
		}

		// Strategy 3: Select2 DOM container
		const $select2Container = $field.next('.select2-container');
		if ($select2Container.length) {
			const labels = getLabelsFromSelect2Container($select2Container, $);
			if (labels.length > 0) {
				const val = $field.val();
				const ids = Array.isArray(val) ? val : val ? [val] : [];
				const combined = [];
				if (isTagField) {
					labels.forEach((label) => {
						if (label) combined.push(label);
					});
				}
				ids.forEach((id) => {
					if (id) combined.push(String(id));
				});
				if (combined.length > 0) {
					$field.attr('data-selected-label', labels.join(','));
					$field.attr('data-selected-id', ids.join(','));
					return combined;
				}
			}
		}

		// Strategy 4: Fallback to select option text and values
		const val = $field.val();
		if (val) {
			const values = Array.isArray(val) ? val : [val];
			if (values.length > 0) {
				const combined = [];
				const labels = [];
				const ids = [];
				const isTagField = $field.is(SELECTORS.TAGS);

				values.forEach((val) => {
					if (isTagField) {
						const tagName = String(val).trim();
						if (tagName) {
							labels.push(tagName);
							combined.push(tagName);
						}
					} else {
						const $option = $field.find(`option[value="${val}"]`);
						if ($option.length) {
							const label = $option.text().trim();
							if (label) labels.push(label);
							ids.push(String(val));
							combined.push(String(val));
						} else {
							combined.push(String(val));
						}
					}
				});

				if (combined.length > 0) {
					if (labels.length > 0) {
						$field.attr('data-selected-label', labels.join(','));
					}
					if (ids.length > 0) {
						$field.attr('data-selected-id', ids.join(','));
					} else if (isTagField && combined.length > 0) {
						$field.attr('data-selected-id', combined.join(','));
					}
					return combined;
				}
			}
		}

		return [];
	}

	// Reset $field for regular fields (not taxonomy selects)
	if ($field && !isTaxonomySelectField($field)) {
		$field = null;
	}

	const mappedSelector = mapFieldKeyToSelector(fieldKey);
	if (mappedSelector) {
		$field = $(mappedSelector).first();
	}

	if (!$field || !$field.length) {
		let potentialFieldKey = WIDGET_KEY_TO_FIELD_KEY[fieldKey] || fieldKey;

		if (
			!fieldKey.startsWith('custom-') &&
			!potentialFieldKey.startsWith('custom-')
		) {
			const customFieldKey = `custom-${fieldKey}`;
			const customFieldIdEscaped = escapeCssId(customFieldKey);
			const $customField = $(
				`[name="${customFieldKey}"], #${customFieldIdEscaped}, .directorist-form-group[data-field-key="${customFieldKey}"] select, .directorist-form-group[data-field-key="${customFieldKey}"] input[type="checkbox"], .directorist-form-group[data-field-key="${customFieldKey}"] input[type="radio"]`
			).first();
			if ($customField.length) {
				potentialFieldKey = customFieldKey;
			}
		}

		const fieldKeyEscaped = escapeCssId(fieldKey);
		const potentialFieldKeyEscaped = escapeCssId(potentialFieldKey);
		const selectors = [
			`[name="${fieldKey}"]`,
			`[name="${fieldKey}[]"]`,
			`#${fieldKeyEscaped}`,
			`[name="${potentialFieldKey}"]`,
			`[name="${potentialFieldKey}[]"]`,
			`#${potentialFieldKeyEscaped}`,
			`.directorist-form-${fieldKeyEscaped}-field input`,
			`.directorist-form-${fieldKeyEscaped}-field select`,
			`.directorist-form-${fieldKeyEscaped}-field textarea`,
			`.directorist-form-${fieldKeyEscaped}-field input[type="file"]`,
			`.directorist-form-${potentialFieldKeyEscaped}-field input`,
			`.directorist-form-${potentialFieldKeyEscaped}-field select`,
			`.directorist-form-${potentialFieldKeyEscaped}-field textarea`,
			`.directorist-form-${potentialFieldKeyEscaped}-field input[type="file"]`,
			`input[name*="${fieldKey}"]`,
			`select[name*="${fieldKey}"]`,
			`input[type="file"][name*="${fieldKey}"]`,
			`input[name*="${potentialFieldKey}"]`,
			`select[name*="${potentialFieldKey}"]`,
			`input[type="file"][name*="${potentialFieldKey}"]`,
			`.directorist-form-group[data-field-key="${fieldKey}"] input`,
			`.directorist-form-group[data-field-key="${fieldKey}"] select`,
			`.directorist-form-group[data-field-key="${fieldKey}"] textarea`,
			`.directorist-form-group[data-field-key="${fieldKey}"] input[type="file"]`,
			`.directorist-form-group[data-field-key="${potentialFieldKey}"] input`,
			`.directorist-form-group[data-field-key="${potentialFieldKey}"] select`,
			`.directorist-form-group[data-field-key="${potentialFieldKey}"] textarea`,
			`.directorist-form-group[data-field-key="${potentialFieldKey}"] input[type="file"]`,
			`.directorist-custom-field-select select[name="${fieldKey}"]`,
			`.directorist-custom-field-select select#${fieldKeyEscaped}`,
			`.directorist-custom-field-select select[name="${potentialFieldKey}"]`,
			`.directorist-custom-field-select select#${potentialFieldKeyEscaped}`,
			`.directorist-form-group.directorist-custom-field-select select[name="${fieldKey}"]`,
			`.directorist-form-group.directorist-custom-field-select select#${fieldKeyEscaped}`,
			`.directorist-form-group.directorist-custom-field-select select[name="${potentialFieldKey}"]`,
			`.directorist-form-group.directorist-custom-field-select select#${potentialFieldKeyEscaped}`,
			`select[name="custom_field[${fieldKey}]"]`,
			`input[name="custom_field[${fieldKey}]"]`,
			`input[name="custom_field[${fieldKey}][]"]`,
			`select[name="custom_field[${potentialFieldKey}]"]`,
			`input[name="custom_field[${potentialFieldKey}]"]`,
			`input[name="custom_field[${potentialFieldKey}][]"]`,
			`.directorist-search-field select[name="custom_field[${fieldKey}]"]`,
			`.directorist-search-field input[name="custom_field[${fieldKey}]"]`,
			`.directorist-search-field select[name="custom_field[${potentialFieldKey}]"]`,
			`.directorist-search-field input[name="custom_field[${potentialFieldKey}]"]`,
			...(fieldKey && !fieldKey.startsWith('custom-')
				? [
						`[name="custom-${fieldKey}"]`,
						`[name="custom-${fieldKey.replace(/_/g, '-')}"]`,
						`select[name="custom_field[custom-${fieldKey.replace(/_/g, '-')}]"]`,
						`input[name="custom_field[custom-${fieldKey.replace(/_/g, '-')}]"]`,
						`#${escapeCssId('custom-' + fieldKey)}`,
						`.directorist-form-group[data-field-key="custom-${fieldKey}"] select`,
						`.directorist-form-group[data-field-key="custom-${fieldKey}"] input`,
						`.directorist-custom-field-select select[name="custom-${fieldKey}"]`,
						`.directorist-custom-field-select select#${escapeCssId('custom-' + fieldKey)}`,
					]
				: []),
		];

		for (let selector of selectors) {
			$field = $(selector).first();
			if ($field.length) {
				break;
			}
		}
	}

	if (!$field || !$field.length) {
		return null;
	}

	// Checkboxes and radio buttons
	if ($field.is(':checkbox') || $field.is(':radio')) {
		if (
			$field.is('[name$="[]"]') ||
			($field.attr('name') && $field.attr('name').includes('[]'))
		) {
			const values = [];
			const nameAttr = $field.attr('name');
			$(`[name="${nameAttr}"]`)
				.filter(':checked')
				.each(function () {
					values.push($(this).val());
				});
			return values;
		}
		if ($field.is(':radio')) {
			const nameAttr = $field.attr('name');
			const $checkedRadio = $(`[name="${nameAttr}"]:checked`);
			return $checkedRadio.length ? $checkedRadio.val() : null;
		}
		return $field.is(':checked') ? $field.val() : null;
	}

	// Multi-select
	if ($field.is('select[multiple]') || $field.prop('multiple')) {
		const val = $field.val();
		return Array.isArray(val) ? val : val ? [val] : [];
	}

	// Select2 fields
	if ($field.hasClass('select2-hidden-accessible')) {
		try {
			const selectedData = $field.select2('data');
			if (selectedData && selectedData.length > 0) {
				return selectedData.map(function (item) {
					return item.text || item.id;
				});
			}
		} catch (e) {}
	}

	// TinyMCE editor
	if (typeof tinymce !== 'undefined' && $field.length) {
		try {
			const editorId = $field.attr('id');
			if (editorId) {
				const editor = tinymce.get(editorId);
				if (
					editor &&
					typeof editor.isHidden === 'function' &&
					!editor.isHidden()
				) {
					const content =
						typeof editor.getContent === 'function'
							? editor.getContent()
							: '';
					if (content) {
						const tempDiv = document.createElement('div');
						tempDiv.innerHTML = content;
						return tempDiv.textContent || tempDiv.innerText || '';
					}
				}
			}
		} catch (e) {
			// TinyMCE may not be ready or editor not found
		}
	}

	// File upload fields
	let $fileWrapper = $field.closest(
		'.directorist-form-group, .directorist-custom-field-file, .directorist-custom-field-file-upload'
	);
	if (!$fileWrapper.length) {
		$fileWrapper = $field.closest(
			'.directorist-form-group, .directorist-custom-field-file, .directorist-custom-field-file-upload'
		);
	}

	const isFileUploadField =
		$field.is('input[type="file"]') ||
		$field.closest('.directorist-custom-field-file').length ||
		$field.closest('.directorist-custom-field-file-upload').length ||
		($fileWrapper.length &&
			($fileWrapper.hasClass('directorist-custom-field-file') ||
				$fileWrapper.hasClass('directorist-custom-field-file-upload') ||
				$fileWrapper.find('.plupload-upload-ui, .plupload-thumbs')
					.length > 0));

	if (isFileUploadField) {
		if (
			$field.is('input[type="file"]') &&
			$field[0] &&
			$field[0].files &&
			$field[0].files.length > 0
		) {
			return 'uploaded';
		}
		if ($fileWrapper.length) {
			const $thumbsContainer = $fileWrapper.find('.plupload-thumbs');
			if (
				$thumbsContainer.length &&
				$thumbsContainer.find('.thumb').length > 0
			) {
				return 'uploaded';
			}
		}
		if ($fileWrapper.length) {
			const fieldKeyFromWrapper =
				$fileWrapper.attr('data-field-key') ||
				$fileWrapper
					.find('[data-field-key]')
					.first()
					.attr('data-field-key');
			if (fieldKeyFromWrapper) {
				const $hiddenInput = $fileWrapper.find(
					`input[type="hidden"][name="${fieldKeyFromWrapper}"]`
				);
				if (
					$hiddenInput.length &&
					$hiddenInput.val() &&
					$hiddenInput.val().trim() !== '' &&
					$hiddenInput.val() !== 'null'
				) {
					return 'uploaded';
				}
			}
		}
		if ($fileWrapper.length) {
			const hasUploadedFiles =
				$fileWrapper.find(
					'.directorist-file-list-item, .directorist-uploaded-file, .directorist-file-item, [data-file-id], .thumb'
				).length > 0 ||
				$fileWrapper
					.find(
						'input[type="hidden"][name*="_file_id"], input[type="hidden"][name*="_file_url"]'
					)
					.filter(function () {
						return $(this).val() && $(this).val().trim() !== '';
					}).length > 0;
			if (hasUploadedFiles) {
				return 'uploaded';
			}
		}
		return null;
	}

	return $field.val() || null;
}
