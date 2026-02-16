/**
 * Init, apply, and event binding for conditional logic
 */
import { normalizeConditionFieldKey } from './field-mapping.js';

export function applyConditionalLogic($fieldWrapper, evaluateConditionalLogicFn, $) {
	const conditionalLogicData = $fieldWrapper.attr('data-conditional-logic');
	if (!conditionalLogicData) {
		return;
	}

	try {
		// Decode HTML entities before parsing JSON
		let decodedData = conditionalLogicData;
		if (typeof decodedData === 'string') {
			// Handle HTML entity encoding (e.g., &quot; -> ")
			const textarea = document.createElement('textarea');
			textarea.innerHTML = decodedData;
			decodedData = textarea.value;
		}
		const conditionalLogic = JSON.parse(decodedData);
		const shouldShow = evaluateConditionalLogicFn(conditionalLogic);

		if (shouldShow) {
			$fieldWrapper.show();
			$fieldWrapper
				.find('input, select, textarea')
				.prop('disabled', false);
			// Sync parent directorist-search-modal__input visibility (search form layout)
			const $modalInput = $fieldWrapper.closest(
				'.directorist-search-modal__input'
			);
			if ($modalInput.length) {
				$modalInput.show();
			}
			// Sync parent directorist-advanced-filter__advanced__element visibility (advanced filter layout)
			const $advancedElement = $fieldWrapper.closest(
				'.directorist-advanced-filter__advanced__element'
			);
			if ($advancedElement.length) {
				$advancedElement.show();
			}
			// Enable TinyMCE editor if present
			if (
				$fieldWrapper.find('textarea').length &&
				typeof tinymce !== 'undefined'
			) {
				const editorId = $fieldWrapper.find('textarea').attr('id');
				if (editorId && tinymce.get(editorId)) {
					tinymce.get(editorId).setMode('design');
				}
			}
		} else {
			$fieldWrapper.hide();
			$fieldWrapper
				.find('input, select, textarea')
				.prop('disabled', true);
			// Sync parent directorist-search-modal__input visibility (search form layout)
			const $modalInput = $fieldWrapper.closest(
				'.directorist-search-modal__input'
			);
			if ($modalInput.length) {
				$modalInput.hide();
			}
			// Sync parent directorist-advanced-filter__advanced__element visibility (advanced filter layout)
			const $advancedElement = $fieldWrapper.closest(
				'.directorist-advanced-filter__advanced__element'
			);
			if ($advancedElement.length) {
				$advancedElement.hide();
			}
			// Disable TinyMCE editor if present
			if (
				$fieldWrapper.find('textarea').length &&
				typeof tinymce !== 'undefined'
			) {
				const editorId = $fieldWrapper.find('textarea').attr('id');
				if (editorId && tinymce.get(editorId)) {
					tinymce.get(editorId).setMode('readonly');
				}
			}
		}
	} catch (e) {
		console.error('Error parsing conditional logic:', e, {
			conditionalLogicData,
		});
	}
}

/**
 * Initialize conditional logic for all fields
 * @param {Function} getWrapperFn
 * @param {Function} getFieldValueFn
 * @param {Function} applyConditionalLogicFn
 * @param {jQuery} $
 * @param {Array} [adminTargets] - Optional. For admin: [{selector, fieldKey, conditionalLogic}]
 */
export function initConditionalLogic(
	getWrapperFn,
	getFieldValueFn,
	applyConditionalLogicFn,
	$,
	adminTargets = []
) {
	// First, update category field label if needed
	const $categoryField = $('#at_biz_dir-categories');
	if ($categoryField.length) {
		// Ensure data-selected-label is up to date (only if Select2-initialized, not admin checklists)
		if (
			$categoryField.hasClass('select2-hidden-accessible') &&
			$categoryField.is('select') &&
			typeof $categoryField.select2 === 'function'
		) {
			try {
				const selectedData = $categoryField.select2('data');
				if (selectedData && selectedData.length > 0) {
					const labels = selectedData
						.map(function (item) {
							return item.text || '';
						})
						.filter(function (item) {
							return item.length > 0;
						})
						.join(',');
					$categoryField.attr('data-selected-label', labels);
				}
			} catch (e) {
				// Ignore errors
			}
		}
	}

	// Apply conditional logic to all fields
	// Search both in form wrapper and globally
	const $formWrapper = $(getWrapperFn());
	let $fieldsWithConditionalLogic = $formWrapper.find(
		'.directorist-form-group[data-conditional-logic]'
	);

	// If not found in form wrapper, search globally (for admin or edge cases)
	if ($fieldsWithConditionalLogic.length === 0) {
		$fieldsWithConditionalLogic = $(
			'.directorist-form-group[data-conditional-logic]'
		);
	}

	$fieldsWithConditionalLogic.each(function () {
		const $fieldWrapper = $(this);
		const fieldKey =
			$fieldWrapper.attr('data-field-key') ||
			$fieldWrapper.find('[id]').first().attr('id') ||
			'unknown';

		applyConditionalLogicFn($fieldWrapper);
	});

	// Admin: apply conditional logic to title/description (WordPress core elements)
	if (
		adminTargets &&
		Array.isArray(adminTargets) &&
		adminTargets.length > 0
	) {
		adminTargets.forEach(function (target) {
			const $el = $(target.selector);
			if ($el.length && target.conditionalLogic) {
				$el.addClass('directorist-conditional-logic-target');
				$el.attr(
					'data-conditional-logic',
					typeof target.conditionalLogic === 'string'
						? target.conditionalLogic
						: JSON.stringify(target.conditionalLogic)
				);
				if (target.fieldKey) {
					$el.attr('data-field-key', target.fieldKey);
				}
				applyConditionalLogicFn($el);
			}
		});
	}
}

/**
 * Watch for field value changes and re-evaluate conditional logic
 */
export function watchFieldChanges(
	getWrapperFn,
	getFieldValueFn,
	applyConditionalLogicFn,
	$
) {
	// Helper function to trigger conditional logic re-evaluation
	function triggerConditionalLogicEvaluation(
		fieldName,
		fieldKey,
		$changedField
	) {
		// Re-evaluate all fields that might depend on this field
		// Include admin targets (title, description) which use directorist-conditional-logic-target
		const $fieldsWithLogic = $(
			'.directorist-form-group[data-conditional-logic], .directorist-conditional-logic-target[data-conditional-logic]'
		);

		$fieldsWithLogic.each(function () {
			const $fieldWrapper = $(this);
			const conditionalLogicData = $fieldWrapper.attr(
				'data-conditional-logic'
			);

			if (!conditionalLogicData) {
				return;
			}

			try {
				// Decode HTML entities before parsing JSON
				let decodedData = conditionalLogicData;
				if (typeof decodedData === 'string') {
					// Handle HTML entity encoding (e.g., &quot; -> ")
					const textarea = document.createElement('textarea');
					textarea.innerHTML = decodedData;
					decodedData = textarea.value;
				}
				const conditionalLogic = JSON.parse(decodedData);

				// Check if this field's conditional logic depends on the changed field
				let dependsOnField = false;
				if (
					conditionalLogic.groups &&
					Array.isArray(conditionalLogic.groups)
				) {
					for (let group of conditionalLogic.groups) {
						if (
							group.conditions &&
							Array.isArray(group.conditions)
						) {
							for (let condition of group.conditions) {
								// Map widget_key to field_key for matching
								const widgetKeyToFieldKeyMap = {
									title: 'listing_title',
									description: 'listing_content',
								};

								const conditionFieldKey = (
									condition.field || ''
								).trim();
								const conditionFieldKeyMapped =
									widgetKeyToFieldKeyMap[conditionFieldKey] ||
									conditionFieldKey;

								// Normalize condition field for custom fields: "select_2" -> "custom-select-2"
								const conditionFieldKeyNormalized =
									normalizeConditionFieldKey(
										conditionFieldKey
									);

								// For custom fields: handle widget_key (e.g., "select") vs field_key (e.g., "custom-select")
								let fieldKeyAsWidgetKey = null;

								// If changed field starts with "custom-", extract the widget_key part for reverse match
								if (
									fieldKey &&
									fieldKey.startsWith('custom-')
								) {
									fieldKeyAsWidgetKey = fieldKey
										.replace(/^custom-/, '')
										.replace(/-/g, '_');
								}
								if (
									fieldName &&
									fieldName.startsWith('custom-')
								) {
									const fieldNameAsWidgetKey =
										fieldName.replace(/^custom-/, '');
									if (!fieldKeyAsWidgetKey) {
										fieldKeyAsWidgetKey =
											fieldNameAsWidgetKey;
									}
								}

								// Check multiple possible field key formats
								// Match by exact field key, field name, or id
								if (
									conditionFieldKey === fieldKey ||
									conditionFieldKey === fieldName ||
									conditionFieldKey ===
										$changedField.attr('id') ||
									conditionFieldKey ===
										$changedField.attr('name') ||
									conditionFieldKeyMapped === fieldKey ||
									conditionFieldKeyMapped === fieldName ||
									conditionFieldKeyMapped ===
										$changedField.attr('id') ||
									conditionFieldKeyMapped ===
										$changedField.attr('name') ||
									// Normalized custom field: "select_2" -> "custom-select-2" matches fieldKey
									(conditionFieldKeyNormalized &&
										(conditionFieldKeyNormalized ===
											fieldKey ||
											conditionFieldKeyNormalized ===
												fieldName ||
											conditionFieldKeyNormalized ===
												$changedField.attr('id') ||
											conditionFieldKeyNormalized ===
												$changedField.attr('name') ||
											`custom_field[${conditionFieldKeyNormalized}]` ===
												fieldName ||
											`custom_field[${conditionFieldKeyNormalized}][]` ===
												fieldName)) ||
									// Reverse: changed field "custom-select-2" matches condition "select_2"
									(fieldKeyAsWidgetKey &&
										(conditionFieldKey ===
											fieldKeyAsWidgetKey ||
											conditionFieldKeyMapped ===
												fieldKeyAsWidgetKey))
								) {
									dependsOnField = true;
									break;
								}
							}
							if (dependsOnField) {
								break;
							}
						}
					}
				}

				// Special handling for category, tag, and location fields
				const isTaxonomyField =
					fieldKey === 'category' ||
					fieldKey === 'categories' ||
					fieldKey === 'in_cat' ||
					fieldKey === 'tag' ||
					fieldKey === 'tags' ||
					fieldKey === 'in_tag' ||
					fieldKey === 'location' ||
					fieldKey === 'locations' ||
					fieldKey === 'in_loc' ||
					fieldName === 'admin_category_select[]' ||
					fieldName === 'tax_input[at_biz_dir-category][]' ||
					fieldName === 'tax_input[at_biz_dir-location][]' ||
					fieldName === 'tax_input[at_biz_dir-tags][]' ||
					fieldName === 'in_cat' ||
					fieldName === 'in_loc' ||
					fieldName === 'in_tag[]' ||
					$changedField.is('#at_biz_dir-categories') ||
					$changedField.is('#at_biz_dir-tags') ||
					$changedField.is('#at_biz_dir-location') ||
					$changedField.is("select[name='in_loc']") ||
					$changedField.is("select[name='in_cat']") ||
					$changedField.is("input[name='in_tag[]']") ||
					$changedField.closest(
						'#at_biz_dir-categorychecklist, #at_biz_dir-categorychecklist-pop'
					).length ||
					$changedField.closest(
						'#at_biz_dir-locationchecklist, #at_biz_dir-locationchecklist-pop'
					).length ||
					$changedField.closest(
						'#at_biz_dir-tagschecklist, #at_biz_dir-tagschecklist-pop, #tagsdiv-at_biz_dir-tags'
					).length;

				if (isTaxonomyField) {
					// Check if any condition references category, tag, or location
					if (
						conditionalLogic.groups &&
						Array.isArray(conditionalLogic.groups)
					) {
						for (let group of conditionalLogic.groups) {
							if (
								group.conditions &&
								Array.isArray(group.conditions)
							) {
								for (let condition of group.conditions) {
									if (
										condition.field === 'category' ||
										condition.field === 'categories' ||
										condition.field ===
											'admin_category_select[]' ||
										condition.field === 'in_cat' ||
										condition.field === 'tag' ||
										condition.field === 'tags' ||
										condition.field === 'in_tag[]' ||
										condition.field === 'location' ||
										condition.field === 'locations' ||
										condition.field ===
											'tax_input[at_biz_dir-location][]' ||
										condition.field === 'in_loc' ||
										condition.field ===
											'tax_input[at_biz_dir-tags][]'
									) {
										dependsOnField = true;
										break;
									}
								}
								if (dependsOnField) {
									break;
								}
							}
						}
					}
				}

				// If this field depends on the changed field, re-evaluate
				if (dependsOnField) {
					applyConditionalLogicFn($fieldWrapper);
				}
			} catch (e) {
				console.error('Error in conditional logic evaluation:', e);
			}
		});
	}

	// Special handling for category, tag, and location field Select2 events
	// Listen on document to catch events even if field is added dynamically
	// Include search form fields (in_loc, in_cat) so they work like submission form
	const taxonomyFieldSelectors =
		"#at_biz_dir-categories, #at_biz_dir-tags, #at_biz_dir-location, select[name='in_loc'], select[name='in_cat']";

	$(document).on(
		'select2:select select2:unselect select2:clear',
		taxonomyFieldSelectors,
		function (e) {
			// Update data attributes immediately when taxonomy field changes
			setTimeout(
				function () {
					const $field = $(this); // The field that triggered the event
					if ($field.length) {
						const labels = [];
						const ids = [];

						// Determine field key based on which field was changed
						let fieldKey = 'category';
						let fieldName = 'admin_category_select[]';

						if ($field.is('#at_biz_dir-tags')) {
							fieldKey = 'tag';
							fieldName = $field.attr('name') || 'tag';
						} else if (
							$field.is('#at_biz_dir-location') ||
							$field.is("select[name='in_loc']")
						) {
							fieldKey = 'location';
							fieldName =
								$field.attr('name') ||
								'tax_input[at_biz_dir-location][]';
						} else if ($field.is("select[name='in_cat']")) {
							fieldKey = 'category';
							fieldName = $field.attr('name') || 'in_cat';
						}

						// Try to get data from Select2 API (only if element is Select2-initialized)
						if (
							$field.hasClass('select2-hidden-accessible') &&
							typeof $field.select2 === 'function'
						) {
							try {
								const selectedData = $field.select2('data');
								if (selectedData && selectedData.length > 0) {
									selectedData.forEach(function (item) {
										if (item.text) labels.push(item.text);
										if (item.id) ids.push(String(item.id));
									});
								}
							} catch (e) {
								// Select2 might throw if not initialized (e.g. admin checklist), continue with DOM
							}
						}

						// Fallback: Read from DOM if Select2 API fails
						if (labels.length === 0 && ids.length === 0) {
							// Try to read from Select2 container
							const $container =
								$field.next('.select2-container');
							if ($container.length) {
								$container
									.find('.select2-selection__choice')
									.each(function () {
										const $choice = $(this);
										const label =
											$choice
												.find(
													'.select2-selection__choice__display'
												)
												.text()
												.trim() ||
											$choice
												.text()
												.trim()
												.replace('×', '')
												.trim();
										if (label) labels.push(label);
									});
							}

							// Get IDs from actual select field value
							const val = $field.val();
							if (val) {
								const values = Array.isArray(val) ? val : [val];
								values.forEach(function (id) {
									if (id) ids.push(String(id));
								});
							}
						}

						// Update data attributes (empty string if no selections)
						$field.attr('data-selected-label', labels.join(','));
						$field.attr('data-selected-id', ids.join(','));

						// Trigger re-evaluation after attributes are updated
						triggerConditionalLogicEvaluation(
							fieldName,
							fieldKey,
							$field
						);
					}
				}.bind(this),
				50
			); // Small delay to ensure Select2 has updated
		}
	);

	// Listen to all form field changes
	$(getWrapperFn()).on(
		'change input select2:select select2:unselect',
		'input, select, textarea, .select2-hidden-accessible',
		function () {
			const $changedField = $(this);
			let fieldName =
				$changedField.attr('name') || $changedField.attr('id');

			if (!fieldName) {
				console.warn(
					'Field change detected but no name/id found:',
					$changedField
				);
				return;
			}

			// Extract field key from name (handle array notation)
			let fieldKey = fieldName;
			if (fieldName.includes('[')) {
				fieldKey = fieldName.split('[')[0];
			}
			if (fieldKey.endsWith('[]')) {
				fieldKey = fieldKey.slice(0, -2);
			}

			// Special handling for category, tag, and location fields
			// Also map WordPress admin title/description to Directorist field keys
			let taxonomyFieldSelector = null;
			if (
				fieldName === 'post_title' ||
				$changedField.attr('id') === 'title'
			) {
				// WordPress admin: title input -> listing_title
				fieldKey = 'listing_title';
			} else if (
				fieldName === 'q' ||
				($changedField.attr('name') === 'q' &&
					$changedField.closest(
						'.directorist-search-query, .directorist-search-form-wrap, .directorist-search-form'
					).length)
			) {
				// Search form: "What are you looking for?" input (name="q") is the title field
				fieldKey = 'title';
			} else if (
				fieldName === 'content' ||
				$changedField.attr('id') === 'content'
			) {
				// WordPress admin: content editor -> listing_content (handled by TinyMCE)
				fieldKey = 'listing_content';
			} else if (
				fieldName === 'admin_category_select[]' ||
				$changedField.is('#at_biz_dir-categories')
			) {
				fieldKey = 'category';
				taxonomyFieldSelector = '#at_biz_dir-categories';
			} else if (
				fieldName === 'tax_input[at_biz_dir-category][]' ||
				$changedField.closest(
					'#at_biz_dir-categorychecklist, #at_biz_dir-categorychecklist-pop'
				).length
			) {
				// Admin: taxonomy metabox checkboxes
				fieldKey = 'admin_category_select[]';
				taxonomyFieldSelector = '#at_biz_dir-categorychecklist';
			} else if (
				fieldName === 'tax_input[at_biz_dir-location][]' ||
				$changedField.closest(
					'#at_biz_dir-locationchecklist, #at_biz_dir-locationchecklist-pop'
				).length
			) {
				// Admin: location taxonomy checkboxes
				fieldKey = 'tax_input[at_biz_dir-location][]';
				taxonomyFieldSelector = '#at_biz_dir-locationchecklist';
			} else if (
				fieldName === 'tax_input[at_biz_dir-tags][]' ||
				$changedField.closest(
					'#at_biz_dir-tagschecklist, #at_biz_dir-tagschecklist-pop, #tagsdiv-at_biz_dir-tags'
				).length
			) {
				// Admin: tags taxonomy checkboxes
				fieldKey = 'tax_input[at_biz_dir-tags][]';
				taxonomyFieldSelector = '#at_biz_dir-tagschecklist';
			} else if ($changedField.is('#at_biz_dir-tags')) {
				fieldKey = 'tag';
				taxonomyFieldSelector = '#at_biz_dir-tags';
			} else if ($changedField.is('#at_biz_dir-location')) {
				fieldKey = 'location';
				taxonomyFieldSelector = '#at_biz_dir-location';
			} else if (
				fieldName === 'in_loc' ||
				$changedField.is("select[name='in_loc']")
			) {
				// Search form: location field (select)
				fieldKey = 'location';
				taxonomyFieldSelector = 'search_form_field'; // Use $changedField
			} else if (
				(fieldName === 'address' ||
					$changedField.is("input[name='address']")) &&
				$changedField.closest('.directorist-search-location').length
			) {
				// Search form: location field (map - typed address)
				fieldKey = 'location';
				taxonomyFieldSelector = 'search_form_address'; // Use $changedField, different update logic
			} else if (
				fieldName === 'in_cat' ||
				$changedField.is("select[name='in_cat']")
			) {
				// Search form: category field
				fieldKey = 'category';
				taxonomyFieldSelector = 'search_form_field';
			} else if (
				fieldName === 'in_tag[]' ||
				$changedField.is("input[name='in_tag[]']")
			) {
				// Search form: tag checkboxes
				fieldKey = 'tag';
				taxonomyFieldSelector = 'search_form_field';
			}

			if (taxonomyFieldSelector) {
				// Update taxonomy field data attributes when it changes
				// For address (typed), skip attribute update and just trigger re-evaluation
				if (taxonomyFieldSelector === 'search_form_address') {
					setTimeout(function () {
						triggerConditionalLogicEvaluation(
							fieldName,
							fieldKey,
							$changedField
						);
					}, 50);
					return;
				}
				const $fieldToUpdate =
					taxonomyFieldSelector === 'search_form_field'
						? $changedField
						: $(taxonomyFieldSelector);
				setTimeout(function () {
					const $taxField = $fieldToUpdate;
					if ($taxField.length) {
						const labels = [];
						const ids = [];

						// Admin checklists (#at_biz_dir-categorychecklist, etc.) are checkbox containers, NOT Select2.
						// Only use Select2 API on elements that are actually Select2-initialized (have select2-hidden-accessible).
						const isChecklist =
							$taxField.attr('id') ===
								'at_biz_dir-categorychecklist' ||
							$taxField.attr('id') ===
								'at_biz_dir-categorychecklist-pop' ||
							$taxField.attr('id') ===
								'at_biz_dir-locationchecklist' ||
							$taxField.attr('id') ===
								'at_biz_dir-locationchecklist-pop' ||
							$taxField.closest(
								'#at_biz_dir-categorychecklist, #at_biz_dir-locationchecklist'
							).length > 0;

						if (
							!isChecklist &&
							$taxField.hasClass('select2-hidden-accessible') &&
							typeof $taxField.select2 === 'function'
						) {
							try {
								const selectedData = $taxField.select2('data');
								if (selectedData && selectedData.length > 0) {
									selectedData.forEach(function (item) {
										if (item.text) labels.push(item.text);
										if (item.id) ids.push(String(item.id));
									});
								}
							} catch (e) {
								// Select2 not initialized, continue with DOM/checkbox reading
							}
						}

						// Admin checklist: read from checked checkboxes (label text = term name, value = term id)
						if (
							isChecklist &&
							labels.length === 0 &&
							ids.length === 0
						) {
							$taxField.find('input:checked').each(function () {
								const $cb = $(this);
								ids.push(String($cb.val()));
								const labelText = $cb
									.closest('label')
									.text()
									.trim();
								if (labelText) labels.push(labelText);
							});
						}

						// Fallback to DOM (Select2 container)
						if (labels.length === 0) {
							const $container =
								$taxField.next('.select2-container');
							if ($container.length) {
								$container
									.find('.select2-selection__choice')
									.each(function () {
										const $choice = $(this);
										const label =
											$choice
												.find(
													'.select2-selection__choice__display'
												)
												.text()
												.trim() ||
											$choice
												.text()
												.trim()
												.replace('×', '')
												.trim();
										if (label) labels.push(label);
									});
							}
						}

						// Get IDs
						const val = $taxField.val();
						if (val) {
							const values = Array.isArray(val) ? val : [val];
							values.forEach(function (id) {
								if (id) ids.push(String(id));
							});
						}

						// Update attributes
						$taxField.attr('data-selected-label', labels.join(','));
						$taxField.attr('data-selected-id', ids.join(','));
					}

					// Trigger evaluation after attributes are updated
					triggerConditionalLogicEvaluation(
						fieldName,
						fieldKey,
						$changedField
					);
				}, 50);
				return; // Don't trigger twice
			}

			triggerConditionalLogicEvaluation(
				fieldName,
				fieldKey,
				$changedField
			);
		}
	);

	// Admin: WordPress tag metabox - tagchecklist (add/remove tags via UI, not checkboxes)
	// Listen for tag add (button click), tag remove (ntdelbutton), and Enter in newtag input
	$(document).on(
		'click',
		'#tagsdiv-at_biz_dir-tags .ntdelbutton, #tagsdiv-at_biz_dir-tags input.tagadd, #tagsdiv-at_biz_dir-tags .button',
		function () {
			setTimeout(function () {
				triggerConditionalLogicEvaluation(
					'tax_input[at_biz_dir-tags][]',
					'tax_input[at_biz_dir-tags][]',
					$('#tagsdiv-at_biz_dir-tags .tagchecklist')
				);
			}, 100);
		}
	);
	$(document).on(
		'keypress',
		'#tagsdiv-at_biz_dir-tags input.newtag, #at_biz_dir-tags input.newtag',
		function (e) {
			if (e.which === 13) {
				setTimeout(function () {
					triggerConditionalLogicEvaluation(
						'tax_input[at_biz_dir-tags][]',
						'tax_input[at_biz_dir-tags][]',
						$(
							'#tagsdiv-at_biz_dir-tags .tagchecklist, #at_biz_dir-tags .tagchecklist'
						)
					);
				}, 50);
			}
		}
	);

	// Admin: MutationObserver for tagchecklist - catches tag add/remove (may load via AJAX)
	function observeTagchecklist() {
		const $tagchecklist = $('#tagsdiv-at_biz_dir-tags .tagchecklist');
		if ($tagchecklist.length && typeof MutationObserver !== 'undefined') {
			const tagObserver = new MutationObserver(function () {
				triggerConditionalLogicEvaluation(
					'tax_input[at_biz_dir-tags][]',
					'tax_input[at_biz_dir-tags][]',
					$tagchecklist
				);
			});
			tagObserver.observe($tagchecklist[0], {
				childList: true,
				subtree: true,
			});
		}
	}
	observeTagchecklist();
	setTimeout(observeTagchecklist, 1000); // Retry if loaded via AJAX

	// Search form: when clear button is clicked, checkboxes/radios are cleared without firing change
	// Trigger re-evaluation so conditional logic updates (e.g. hide fields when tag is cleared)
	$(document).on(
		'click',
		'.directorist-search-field__btn--clear',
		function () {
			const $fieldWrap = $(this).closest('.directorist-search-field');
			if (!$fieldWrap.length) return;
			let fieldKey = null;
			let fieldName = null;
			let $changedField = null;
			if ($fieldWrap.find('input[name="in_tag[]"]').length) {
				fieldKey = 'tag';
				fieldName = 'in_tag[]';
				$changedField = $fieldWrap
					.find('input[name="in_tag[]"]')
					.first();
			} else if ($fieldWrap.find("select[name='in_cat']").length) {
				fieldKey = 'category';
				fieldName = 'in_cat';
				$changedField = $fieldWrap
					.find("select[name='in_cat']")
					.first();
			} else if ($fieldWrap.find("select[name='in_loc']").length) {
				fieldKey = 'location';
				fieldName = 'in_loc';
				$changedField = $fieldWrap
					.find("select[name='in_loc']")
					.first();
			} else if (
				$fieldWrap.find('input[name="address"]').length &&
				$fieldWrap.hasClass('directorist-search-location')
			) {
				fieldKey = 'location';
				fieldName = 'address';
				$changedField = $fieldWrap
					.find('input[name="address"]')
					.first();
			} else if (
				$fieldWrap.find('input[name="q"]').length ||
				$fieldWrap.hasClass('directorist-search-query')
			) {
				fieldKey = 'title';
				fieldName = 'q';
				$changedField = $fieldWrap.find('input[name="q"]').first();
			}
			if (fieldKey) {
				setTimeout(function () {
					triggerConditionalLogicEvaluation(
						fieldName,
						fieldKey,
						$changedField
					);
				}, 50);
			}
		}
	);

	// Also listen on document level as fallback for custom fields that might be outside the form wrapper
	$(document).on(
		'change',
		'.directorist-select select, .directorist-custom-field-select select, select.directorist-form-element, .directorist-custom-field-radio input[type="radio"], .directorist-custom-field-checkbox input[type="checkbox"]',
		function () {
			const $changedField = $(this);
			let fieldName =
				$changedField.attr('name') || $changedField.attr('id');

			if (!fieldName) {
				return;
			}

			// Extract field key from name
			let fieldKey = fieldName;
			if (fieldName.includes('[')) {
				fieldKey = fieldName.split('[')[0];
			}
			if (fieldKey.endsWith('[]')) {
				fieldKey = fieldKey.slice(0, -2);
			}

			triggerConditionalLogicEvaluation(
				fieldName,
				fieldKey,
				$changedField
			);
		}
	);

	/**
	 * Handle color picker field change for conditional logic
	 * Extracts field name/key and triggers conditional logic evaluation with a delay
	 * to ensure the input value is updated in the DOM
	 *
	 * @param {jQuery|HTMLElement} field - The color picker input field (jQuery object or DOM element)
	 */
	function handleColorPickerChange(field) {
		const $changedField = $(field);
		let fieldName = $changedField.attr('name') || $changedField.attr('id');

		if (!fieldName) {
			return;
		}

		// Extract field key from name
		let fieldKey = fieldName;
		if (fieldName.includes('[')) {
			fieldKey = fieldName.split('[')[0];
		}
		if (fieldKey.endsWith('[]')) {
			fieldKey = fieldKey.slice(0, -2);
		}

		// Use setTimeout to ensure the input value is updated after color change
		// The color picker updates the value asynchronously, so we need a small delay
		setTimeout(function () {
			triggerConditionalLogicEvaluation(
				fieldName,
				fieldKey,
				$changedField
			);
		}, 50);
	}

	// Also listen for wpColorPicker's change event directly on the input
	// This catches cases where the custom event might not fire
	$(document).on(
		'change',
		'.directorist-color-picker, .wp-color-picker, input.wp-color-picker',
		function () {
			handleColorPickerChange(this);
		}
	);

	// Also listen for iris color change events (fired by wpColorPicker internally)
	// This is a more direct way to catch color picker changes
	// Note: irischange fires during color selection, but the value might not be set yet
	$(document).on(
		'irischange',
		'.directorist-color-picker, .wp-color-picker, input.wp-color-picker',
		function () {
			handleColorPickerChange(this);
		}
	);

	// Listen for color picker clear button click
	// Note: The button is dynamically added to DOM when color picker is opened
	// We use native addEventListener with capture phase to catch the event
	// before other handlers that might stop propagation
	// This is necessary because the button is created dynamically by wpColorPicker
	document.addEventListener(
		'click',
		function (e) {
			if (
				e.target &&
				(e.target.classList.contains('wp-picker-clear') ||
					(e.target.tagName === 'INPUT' &&
						e.target.type === 'button' &&
						e.target.className.includes('wp-picker-clear')))
			) {
				// Find the associated color picker input
				// e.target is a DOM element, so we need to wrap it in jQuery
				const $clearButton = $(e.target);
				const $colorPickerInput = $clearButton
					.closest('.wp-picker-container')
					.find(
						'.directorist-color-picker, .wp-color-picker, input.wp-color-picker'
					);
				// Trigger conditional logic evaluation
				handleColorPickerChange($colorPickerInput);
			}
		},
		true
	);

	// Listen to file upload events (plupload and ez-media-uploader)
	// Use MutationObserver to watch for when files are uploaded or removed
	const fileUploadObserver = new MutationObserver(function (mutations) {
		mutations.forEach(function (mutation) {
			// Handle attribute changes (class changes - for ezmu--show class)
			if (
				mutation.type === 'attributes' &&
				mutation.attributeName === 'class'
			) {
				const $target = $(mutation.target);
				// Check if ezmu--show class was added to preview section
				if (
					$target.hasClass('ezmu__preview-section') &&
					$target.hasClass('ezmu--show')
				) {
					const $imageWrapper = $target.closest(
						'.directorist-form-image-upload-field'
					);
					if ($imageWrapper.length) {
						const fieldKey = 'listing_img';
						setTimeout(function () {
							triggerConditionalLogicEvaluation(
								fieldKey,
								fieldKey,
								$imageWrapper.find('.ez-media-uploader').first()
							);
						}, 200);
					}
				}
				// Also check if ezmu--show was removed (image deleted)
				if (
					$target.hasClass('ezmu__preview-section') &&
					!$target.hasClass('ezmu--show')
				) {
					const $imageWrapper = $target.closest(
						'.directorist-form-image-upload-field'
					);
					if ($imageWrapper.length) {
						const fieldKey = 'listing_img';
						setTimeout(function () {
							triggerConditionalLogicEvaluation(
								fieldKey,
								fieldKey,
								$imageWrapper.find('.ez-media-uploader').first()
							);
						}, 200);
					}
				}
			}

			// Handle added nodes (file uploads)
			if (mutation.addedNodes.length > 0) {
				mutation.addedNodes.forEach(function (node) {
					if (node.nodeType === 1) {
						// Element node
						const $node = $(node);

						// Check for plupload thumbnails
						if (
							$node.hasClass('thumb') ||
							$node.closest('.plupload-thumbs').length ||
							$node.find('.thumb').length
						) {
							// Find the file upload field wrapper
							const $fileWrapper = $node.closest(
								'.directorist-form-group, .directorist-custom-field-file-upload'
							);
							if ($fileWrapper.length) {
								let fieldKey =
									$fileWrapper.attr('data-field-key') ||
									$fileWrapper
										.find('[data-field-key]')
										.first()
										.attr('data-field-key');

								// If we don't have field key, try to get it from hidden input
								if (!fieldKey) {
									const $hiddenInput = $fileWrapper
										.find('input[type="hidden"]')
										.first();
									if ($hiddenInput.length) {
										let inputName =
											$hiddenInput.attr('name');
										if (inputName) {
											if (inputName.includes('[')) {
												inputName =
													inputName.split('[')[0];
											}
											fieldKey = inputName;
										}
									}
								}

								if (fieldKey) {
									// Trigger conditional logic evaluation after a short delay
									// to ensure DOM is fully updated
									setTimeout(function () {
										triggerConditionalLogicEvaluation(
											fieldKey,
											fieldKey,
											$fileWrapper
												.find('input[type="hidden"]')
												.first()
										);
									}, 100);
								}
							}
						}

						// Check for ez-media-uploader image uploads (listing_img)
						// Check for preview section with ezmu--show class or file items
						if (
							$node.hasClass('ezmu__preview-section') ||
							$node.hasClass('ezmu--show') ||
							$node.closest('.ezmu__preview-section.ezmu--show')
								.length
						) {
							// Find the image upload field wrapper
							const $imageWrapper = $node.closest(
								'.directorist-form-image-upload-field'
							);
							if ($imageWrapper.length) {
								const fieldKey = 'listing_img';
								// Trigger conditional logic evaluation after a delay
								setTimeout(function () {
									triggerConditionalLogicEvaluation(
										fieldKey,
										fieldKey,
										$imageWrapper
											.find('.ez-media-uploader')
											.first()
									);
								}, 200);
							}
						}
					}
				});
			}

			// Handle removed nodes (file deletions)
			if (mutation.removedNodes.length > 0) {
				mutation.removedNodes.forEach(function (node) {
					if (node.nodeType === 1) {
						// Element node
						const $node = $(node);

						// Check if a thumbnail was removed from plupload-thumbs container
						if (
							$node.hasClass('thumb') ||
							$node.closest('.plupload-thumbs').length ||
							$node.find('.thumb').length
						) {
							// Find the file upload field wrapper from the parent container
							const $thumbsContainer = $(mutation.target);
							if (
								$thumbsContainer.hasClass('plupload-thumbs') ||
								$thumbsContainer.find('.plupload-thumbs').length
							) {
								const $fileWrapper = $thumbsContainer.closest(
									'.directorist-form-group, .directorist-custom-field-file-upload'
								);
								if ($fileWrapper.length) {
									let fieldKey =
										$fileWrapper.attr('data-field-key') ||
										$fileWrapper
											.find('[data-field-key]')
											.first()
											.attr('data-field-key');

									// If we don't have field key, try to get it from hidden input
									if (!fieldKey) {
										const $hiddenInput = $fileWrapper
											.find('input[type="hidden"]')
											.first();
										if ($hiddenInput.length) {
											let inputName =
												$hiddenInput.attr('name');
											if (inputName) {
												if (inputName.includes('[')) {
													inputName =
														inputName.split('[')[0];
												}
												fieldKey = inputName;
											}
										}
									}

									if (fieldKey) {
										// Trigger conditional logic evaluation after a delay
										// to ensure plupload has finished updating the hidden input
										setTimeout(function () {
											triggerConditionalLogicEvaluation(
												fieldKey,
												fieldKey,
												$fileWrapper
													.find(
														'input[type="hidden"]'
													)
													.first()
											);
										}, 300);
									}
								}
							}
						}

						// Check for ez-media-uploader image removals (listing_img)
						if (
							$node.hasClass('ezmu__file-item') ||
							$node.hasClass('ezmu__new-file') ||
							$node.closest('.ez-media-uploader').length ||
							$node.hasClass('ezmu__old-files-meta') ||
							$node.find('.ezmu__file-item, .ezmu__new-file')
								.length
						) {
							// Find the image upload field wrapper from the parent container
							const $uploaderContainer = $(mutation.target);
							if (
								$uploaderContainer.hasClass(
									'ez-media-uploader'
								) ||
								$uploaderContainer.closest('.ez-media-uploader')
									.length
							) {
								const $imageWrapper =
									$uploaderContainer.closest(
										'.directorist-form-image-upload-field'
									);
								if ($imageWrapper.length) {
									const fieldKey = 'listing_img';
									// Trigger conditional logic evaluation after a delay
									setTimeout(function () {
										triggerConditionalLogicEvaluation(
											fieldKey,
											fieldKey,
											$imageWrapper
												.find('.ez-media-uploader')
												.first()
										);
									}, 300);
								}
							}
						}
					}
				});
			}
		});
	});

	// Start observing the document body for changes
	fileUploadObserver.observe(document.body, {
		childList: true,
		subtree: true,
		attributes: true, // Watch for attribute changes (like class changes)
		attributeFilter: ['class'], // Only watch for class attribute changes
	});

	// Also listen for click events on file remove buttons
	// Use native event listener with capture phase to catch early
	document.addEventListener(
		'click',
		function (e) {
			// Check if the clicked element or its parent is a thumbremovelink
			const $target = $(e.target);
			const $removeButton =
				$target.closest('.thumbremovelink').length > 0
					? $target.closest('.thumbremovelink')
					: $target.hasClass('thumbremovelink')
						? $target
						: null;

			if (!$removeButton || !$removeButton.length) {
				return;
			}

			// Find the file upload field wrapper
			const $thumb = $removeButton.closest('.thumb');
			if (!$thumb.length) {
				return;
			}

			const $fileWrapper = $thumb.closest(
				'.directorist-form-group, .directorist-custom-field-file-upload'
			);

			if ($fileWrapper.length) {
				// Extract field key from the hidden input or data attribute
				let fieldKey =
					$fileWrapper.attr('data-field-key') ||
					$fileWrapper
						.find('[data-field-key]')
						.first()
						.attr('data-field-key');

				// If we don't have field key from data attribute, try to get it from hidden input
				if (!fieldKey) {
					const $hiddenInput = $fileWrapper
						.find('input[type="hidden"]')
						.first();
					if ($hiddenInput.length) {
						let inputName = $hiddenInput.attr('name');
						if (inputName) {
							// Remove array notation if present
							if (inputName.includes('[')) {
								inputName = inputName.split('[')[0];
							}
							fieldKey = inputName;
						}
					}
				}

				if (fieldKey) {
					// Wait for plupload to update the DOM and hidden input value
					// plu_show_thumbs is called after the click, so we need to wait longer
					setTimeout(function () {
						const $hiddenInput = $fileWrapper
							.find(
								`input[type="hidden"][name="${fieldKey}"], input[type="hidden"][name="${fieldKey}[]"]`
							)
							.first();
						if (!$hiddenInput.length) {
							// Try to find any hidden input in the wrapper
							const $anyHiddenInput = $fileWrapper
								.find('input[type="hidden"]')
								.first();
							triggerConditionalLogicEvaluation(
								fieldKey,
								fieldKey,
								$anyHiddenInput.length
									? $anyHiddenInput
									: $fileWrapper
							);
						} else {
							triggerConditionalLogicEvaluation(
								fieldKey,
								fieldKey,
								$hiddenInput
							);
						}
					}, 400); // Increased delay to ensure plupload has finished updating
				}
			}
		},
		true // Use capture phase
	);

	// Listen to TinyMCE editor changes
	// Helper function to attach TinyMCE event listeners
	function attachTinyMCEEvents(editor) {
		if (!editor || !editor.id) {
			return;
		}

		const editorId = editor.id;
		const $editorTextarea = $('#' + editorId);
		if (!$editorTextarea.length) {
			return;
		}

		// Include: (1) editors inside directorist-form-group, (2) WordPress admin content editor (#content)
		const $formGroup = $editorTextarea.closest('.directorist-form-group');
		const isWordPressContentEditor =
			editorId === 'content' &&
			$editorTextarea.closest('#postdivrich, #wp-content-wrap').length;
		if (!$formGroup.length && !isWordPressContentEditor) {
			return;
		}

		// Get the field key from the textarea name or id
		const fieldName = $editorTextarea.attr('name') || editorId;
		let fieldKey = fieldName;

		// Map widget_key to field_key (WordPress uses "content" for post body)
		const widgetKeyToFieldKeyMap = {
			title: 'listing_title',
			description: 'listing_content',
			content: 'listing_content',
		};
		fieldKey = widgetKeyToFieldKeyMap[fieldKey] || fieldKey;

		// Remove existing listeners to avoid duplicates
		editor.off('input keyup change NodeChange');

		// Listen to editor content changes
		editor.on('input keyup change NodeChange', function () {
			const $changedField = $editorTextarea;
			triggerConditionalLogicEvaluation(
				fieldName,
				fieldKey,
				$changedField
			);
		});
	}

	// Set up TinyMCE listeners when available
	if (typeof tinymce !== 'undefined') {
		// Wait for TinyMCE to be ready
		$(document).ready(function () {
			// Use TinyMCE's AddEditor event to attach listeners to new editors
			if (tinymce.on) {
				tinymce.on('AddEditor', function (e) {
					attachTinyMCEEvents(e.editor);
				});
			}

			// Handle editors that are already initialized
			function initExistingEditors() {
				if (typeof tinymce !== 'undefined' && tinymce.editors) {
					tinymce.editors.forEach(function (editor) {
						attachTinyMCEEvents(editor);
					});
				}
			}

			// Try immediately
			initExistingEditors();

			// Also try after a delay to catch late-loading editors
			setTimeout(initExistingEditors, 500);
			setTimeout(initExistingEditors, 1000);
			setTimeout(initExistingEditors, 2000);
		});

		// Listen to WordPress TinyMCE setup events
		$(document).on('tinymce-editor-init', function (e, editor) {
			attachTinyMCEEvents(editor);
		});
	}
}

/**
 * Update category field data-selected-label attribute from Select2
 */
export function updateCategoryFieldLabel(initConditionalLogicFn, $) {
	const $field = $('#at_biz_dir-categories');
	if (!$field.length) {
		return;
	}

	setTimeout(function () {
		// Get selected labels from Select2 (only if element is select and Select2-initialized)
		if (
			$field.is('select') &&
			$field.hasClass('select2-hidden-accessible') &&
			typeof $field.select2 === 'function'
		) {
			try {
				const selectedData = $field.select2('data');
				if (selectedData && selectedData.length > 0) {
					const labels = selectedData
						.map(function (item) {
							return item.text || '';
						})
						.filter(function (item) {
							return item.length > 0;
						})
						.join(',');
					$field.attr('data-selected-label', labels);
				} else {
					$field.attr('data-selected-label', '');
				}
			} catch (e) {
				// Select2 might not be initialized yet, try reading from DOM
				const $select2Container = $('.select2-selection__choice');
				if ($select2Container.length) {
					const labels = [];
					$select2Container.each(function () {
						const label = $(this)
							.find('.select2-selection__choice__display')
							.text()
							.trim();
						if (label) {
							labels.push(label);
						}
					});
					if (labels.length > 0) {
						$field.attr('data-selected-label', labels.join(','));
					}
				}
			}
		}

		// Re-evaluate conditional logic
		initConditionalLogicFn();
	}, 150);
}
