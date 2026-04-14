/**
 * Taxonomy field handlers: Select2, tag metabox, clear button
 */
import { SELECTORS } from '../field-mapping.js';
import { syncSelect2DataAttributes } from '../helpers.js';

/**
 * @param {jQuery} $
 * @param {Function} triggerFn - (fieldName, fieldKey, $changedField) => void
 */
export function setupTaxonomyHandlers($, triggerFn) {
	const taxonomyFieldSelectors = `${SELECTORS.CATEGORY}, ${SELECTORS.TAGS}, ${SELECTORS.LOCATION}, ${SELECTORS.IN_LOC}, ${SELECTORS.IN_CAT}`;

	// Select2: delay so DOM is updated before we read data-selected-*
	// 'change' needed for search form in_cat/in_loc (Select2 may not fire select2:select in some setups)
	$(document).on(
		'change select2:select select2:unselect select2:clear',
		taxonomyFieldSelectors,
		function () {
			setTimeout(
				function () {
					const $field = $(this);
					if (!$field.length) return;

					let fieldKey = 'category';
					let fieldName = 'admin_category_select[]';

					if ($field.is(SELECTORS.TAGS)) {
						fieldKey = 'tag';
						fieldName = $field.attr('name') || 'tag';
					} else if (
						$field.is(SELECTORS.LOCATION) ||
						$field.is(SELECTORS.IN_LOC)
					) {
						fieldKey = 'location';
						fieldName =
							$field.attr('name') ||
							'tax_input[at_biz_dir-location][]';
					} else if ($field.is(SELECTORS.IN_CAT)) {
						fieldKey = 'category';
						fieldName = $field.attr('name') || 'in_cat';
					}

					syncSelect2DataAttributes($field, $);
					triggerFn(fieldName, fieldKey, $field);
				}.bind(this),
				50
			);
		}
	);

	// Tag metabox: add/remove tags
	$(document).on(
		'click',
		`${SELECTORS.TAG_METABOX} .ntdelbutton, ${SELECTORS.TAG_METABOX} input.tagadd, ${SELECTORS.TAG_METABOX} .button`,
		function () {
			setTimeout(function () {
				triggerFn(
					'tax_input[at_biz_dir-tags][]',
					'tax_input[at_biz_dir-tags][]',
					$(`${SELECTORS.TAG_METABOX} .tagchecklist`)
				);
			}, 100);
		}
	);
	$(document).on(
		'keypress',
		`${SELECTORS.TAG_METABOX} input.newtag, ${SELECTORS.TAGS} input.newtag`,
		function (e) {
			if (e.which === 13) {
				setTimeout(function () {
					triggerFn(
						'tax_input[at_biz_dir-tags][]',
						'tax_input[at_biz_dir-tags][]',
						$(
							`${SELECTORS.TAG_METABOX} .tagchecklist, ${SELECTORS.TAGS} .tagchecklist`
						)
					);
				}, 50);
			}
		}
	);

	// MutationObserver: tagchecklist may load via AJAX
	function observeTagchecklist() {
		const $tagchecklist = $(`${SELECTORS.TAG_METABOX} .tagchecklist`);
		if ($tagchecklist.length && typeof MutationObserver !== 'undefined') {
			const tagObserver = new MutationObserver(function () {
				triggerFn(
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
	setTimeout(observeTagchecklist, 1000);
}
