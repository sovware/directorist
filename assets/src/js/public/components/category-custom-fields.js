// Search Category Change
function hideAllCustomFieldsExceptSelected(relations, category, $container) {
	const fields = Object.keys(relations);
	const wrappers = [
		'.directorist-advanced-filter__advanced__element',
		'.directorist-search-modal__input',
		'.directorist-search-field',
	];

	if (!fields.length) {
		return;
	}

	fields.forEach((field) => {
		const fieldCategory = relations[field];
		let $field = $container.find(`[name="custom_field\[${field}]"\]`);

		if (!$field.length) {
			$field = $container.find(`[name="custom_field\[${field}][]"\]`);
		}

		if (category === fieldCategory) {
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
	const $searchPageContainer = $('.directorist-search-contents');
	const $archivePageContainer = $('.directorist-archive-contents');

	let $pageContainer;

	if ($searchPageContainer.length) {
		$pageContainer = $searchPageContainer;
	} else if ($archivePageContainer.length) {
		$pageContainer = $archivePageContainer;
	}

	if ($pageContainer?.length) {
		// let $fieldsContainer = null;

		$pageContainer.on(
			'change',
			'.directorist-category-select, .directorist-search-category select',
			function (event) {
				const $this = $(this);
				const $form = $this.parents('form');
				const category = Number($this.val());
				let attributes = $form.data('atts');

				if (!attributes) {
					attributes = $pageContainer.data('atts');
				}

				if (!attributes.category_custom_fields_relations) {
					return;
				}

				hideAllCustomFieldsExceptSelected(
					attributes.category_custom_fields_relations,
					category,
					$(document.body)
				);
			}
		);

		$pageContainer
			.find(
				'.directorist-category-select, .directorist-search-category select'
			)
			.trigger('change');
	}
}
