// Search Category Change
function hideAllCustomFieldsExceptSelected(relations, categories, $container) {
	const fields = Object.keys(relations);
	const wrappers = [
		'.directorist-advanced-filter__advanced__element',
		'.directorist-search-modal__input',
		'.directorist-search-field',
	];

	if (!fields.length) {
		return;
	}

	// Convert categories to array if it's not already
	const categoryArray = Array.isArray(categories) ? categories : [categories];

	fields.forEach((field) => {
		const fieldCategory = relations[field];

		// Try multiple selectors to find the field
		let $field = null;
		const selectors = [
			`[name="custom_field[${field}]"]`,
			`[name="custom_field[${field}][]"]`,
			`[name*="${field}"]`,
			`[data-field-key="${field}"]`,
			`[id*="${field}"]`,
		];

		for (const selector of selectors) {
			$field = $container.find(selector);
			if ($field.length > 0) {
				break;
			}
		}

		if (!$field || !$field.length) {
			return;
		}

		// Check if the field category matches any of the selected categories
		const shouldShow = categoryArray.some(
			(category) => Number(category) === Number(fieldCategory)
		);

		if (shouldShow) {
			$field.prop('disabled', false);

			wrappers.forEach((wrapper) => {
				const $wrapper = $field.closest(wrapper);
				if ($wrapper.length) {
					$wrapper.show();
				}
			});
		} else {
			$field.prop('disabled', true);

			wrappers.forEach((wrapper) => {
				const $wrapper = $field.closest(wrapper);
				if ($wrapper.length) {
					$wrapper.hide();
				}
			});
		}
	});
}

export default function initSearchCategoryCustomFields($) {
	// Handle multiple search forms and containers
	const containers = [
		'.directorist-search-contents',
		'.directorist-archive-contents',
		'.directorist-search-form',
		'.directorist-add-listing-form',
	];

	containers.forEach((containerSelector) => {
		const $container = $(containerSelector);

		if ($container.length) {
			// Bind events to all category selects within this container
			$container.on(
				'change',
				'.directorist-category-select, .directorist-search-category select, .bdas-category-search',
				function (event) {
					const $this = $(this);
					const $form = $this.parents('form');
					let categories = $this.val();
					let attributes = $form.data('atts');

					// If form doesn't have attributes, try container
					if (!attributes) {
						attributes = $container.data('atts');
					}

					// If still no attributes, try document body
					if (!attributes) {
						attributes = $(document.body).data('atts');
					}

					if (
						!attributes ||
						!attributes.category_custom_fields_relations
					) {
						return;
					}

					// Handle both single and multiple category selections
					if (categories) {
						// Convert to array if it's a single value
						if (!Array.isArray(categories)) {
							categories = [categories];
						}
						// Convert string values to numbers and filter out empty values
						categories = categories
							.map((cat) => Number(cat))
							.filter((cat) => cat > 0); // Filter out 0, null, undefined, etc.
					} else {
						categories = [];
					}

					// Use the specific container for field search to avoid conflicts
					hideAllCustomFieldsExceptSelected(
						attributes.category_custom_fields_relations,
						categories,
						$container
					);
				}
			);

			// Trigger change event on page load for all category selects in this container
			$container
				.find(
					'.directorist-category-select, .directorist-search-category select, .bdas-category-search'
				)
				.each(function () {
					$(this).trigger('change');
				});
		}
	});

	// Also handle global category selects that might not be in specific containers
	const globalSelectors =
		'.directorist-category-select, .directorist-search-category select, .bdas-category-search';

	$(document).on('change', globalSelectors, function (event) {
		const $this = $(this);

		// Only handle if not already handled by container-specific handlers
		if (!event.isDefaultPrevented()) {
			const $form = $this.parents('form');
			let categories = $this.val();
			let attributes = $form.data('atts');

			if (!attributes) {
				attributes = $(document.body).data('atts');
			}

			if (!attributes || !attributes.category_custom_fields_relations) {
				return;
			}

			// Handle both single and multiple category selections
			if (categories) {
				if (!Array.isArray(categories)) {
					categories = [categories];
				}
				categories = categories
					.map((cat) => Number(cat))
					.filter((cat) => cat > 0);
			} else {
				categories = [];
			}

			hideAllCustomFieldsExceptSelected(
				attributes.category_custom_fields_relations,
				categories,
				$(document.body)
			);
		}
	});
}
