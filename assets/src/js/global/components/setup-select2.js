import { convertToSelect2 } from './../../lib/helper';
import './select2-custom-control';

const $ = jQuery;

window.addEventListener('load', initSelect2);
document.body.addEventListener(
	'directorist-search-form-nav-tab-reloaded',

	initSelect2
);
document.body.addEventListener(
	'directorist-reload-select2-fields',
	initSelect2
);
window.addEventListener('directorist-instant-search-reloaded', initSelect2);

// Init Static Select 2 Fields
function initSelect2() {
	const selectors = [
		'.directorist-select select',
		'#directorist-select-js', // Not found in any template
		'#directorist-search-category-js', // Not found in any template
		// '#directorist-select-st-s-js',
		// '#directorist-select-sn-s-js',
		// '#directorist-select-mn-e-js',
		// '#directorist-select-tu-e-js',
		// '#directorist-select-wd-s-js',
		// '#directorist-select-wd-e-js',
		// '#directorist-select-th-e-js',
		// '#directorist-select-fr-s-js',
		// '#directorist-select-fr-e-js',
		'.select-basic', // Not found in any template
		'#loc-type',
		'#cat-type',
		'#at_biz_dir-category',
		'.bdas-location-search', // Not found in any template
		'.bdas-category-search', // Not found in any template
	];

	selectors.forEach((selector) => convertToSelect2(selector));

	initMaybeLazyLoadedTaxonomySelect2();
}

// Init Select2 Ajax Fields
function initMaybeLazyLoadedTaxonomySelect2() {
	const restBase = `${directorist.rest_url}directorist/v1`;

	maybeLazyLoadCategories({
		selector: '.directorist-search-category select',
		url: `${restBase}/listings/categories`,
	});

	maybeLazyLoadCategories({
		selector: '.directorist-form-categories-field select',
		url: `${restBase}/listings/categories`,
	});

	maybeLazyLoadLocations({
		selector: '.directorist-search-location select',
		url: `${restBase}/listings/locations`,
	});

	maybeLazyLoadLocations({
		selector: '.directorist-form-location-field select',
		url: `${restBase}/listings/locations`,
	});

	maybeLazyLoadTags({
		selector: '.directorist-form-tag-field select',
		url: `${restBase}/listings/tags`,
	});
}

function maybeLazyLoadCategories(args) {
	maybeLazyLoadTaxonomyTermsSelect2({
		...{ taxonomy: 'categories' },
		...args,
	});
}

function maybeLazyLoadLocations(args) {
	maybeLazyLoadTaxonomyTermsSelect2({
		...{ taxonomy: 'locations' },
		...args,
	});
}

function maybeLazyLoadTags(args) {
	maybeLazyLoadTaxonomyTermsSelect2({ ...{ taxonomy: 'tags' }, ...args });
}

// maybeLazyLoadTaxonomyTermsSelect2
function maybeLazyLoadTaxonomyTermsSelect2(args) {
	const defaults = {
		selector: '',
		url: '',
		taxonomy: 'tags',
	};

	args = { ...defaults, ...args };

	if (!args.selector) {
		return;
	}

	const $el = $(args.selector);
	const $addListing = $el.closest('.directorist-add-listing-form');
	const canCreate = $el.data('allow_new');
	const maxLength = $el.data('max');
	let directoryId = 0;

	if (args.taxonomy !== 'tags') {
		const $searchForm = $el.closest('.directorist-search-form');
		const $archivePage = $el.closest('.directorist-archive-contents');
		const $directory = $addListing.find('input[name="directory_type"]');
		let $navListItem = null;

		// If search page
		if ($searchForm.length) {
			$navListItem = $searchForm.find(
				'.directorist-listing-type-selection__link--current'
			);
		}

		if ($archivePage.length) {
			$navListItem = $archivePage.find(
				'.directorist-type-nav__list li.directorist-type-nav__list__current .directorist-type-nav__link'
			);
		}

		if ($navListItem && $navListItem.length) {
			directoryId = Number($navListItem.data('listing_type_id'));
		}

		if ($directory.length) {
			directoryId = $directory.val();
		}

		if (directoryId) {
			directoryId = Number(directoryId);
		}
	}

	let currentPage = 1;

	const select2Options = {
		allowClear: true,
		tags: canCreate,
		maximumSelectionLength: maxLength,
		width: '100%',
		escapeMarkup: function (text) {
			return text;
		},
		templateResult: function (data) {
			if (!data.id) {
				return data.text;
			}

			// Fetch the data-icon attribute
			const iconURI = $(data.element).attr('data-icon');

			// Get the original text
			let originalText = data.text;

			// Match and count leading spaces
			const leadingSpaces = originalText.match(/^\s+/);
			const spaceCount = leadingSpaces ? leadingSpaces[0].length : 0;

			// Trim leading spaces from the original text
			originalText = originalText.trim();

			// Construct the icon element
			const iconElm = iconURI
				? `<i class="directorist-icon-mask" aria-hidden="true" style="--directorist-icon: url('${iconURI}')"></i>`
				: '';

			// Prepare the combined text (icon + text)
			const combinedText = iconElm + originalText;

			// Create the state container
			const $state = $(
				'<div class="directorist-select2-contents"></div>'
			);

			// Determine the level based on space count
			let level = Math.floor(spaceCount / 8) + 1; // 8 spaces = level 2, 16 spaces = level 3, etc.
			if (level > 1) {
				$state.addClass('item-level-' + level); // Add class for the level (e.g., level-1, level-2, etc.)
			}

			$state.html(combinedText); // Set the combined content (icon + text)

			return $state;
		},
	};

	if (directorist.lazy_load_taxonomy_fields) {
		select2Options.ajax = {
			url: args.url,
			dataType: 'json',
			cache: true,
			delay: 250,
			data: function (params) {
				currentPage = params.page || 1;

				let query = {
					page: currentPage,
					per_page: args.perPage,
					hide_empty: true,
				};

				// Load empty terms on add listings.
				if ($addListing.length) {
					query.hide_empty = false;
				}

				if (params.term) {
					query.search = params.term;
					query.hide_empty = false;
				}

				if (directoryId) {
					query.directory = directoryId;
				}

				return query;
			},

			processResults: function (data) {
				return {
					results: data.items,
					pagination: { more: data.paginationMore },
				};
			},

			transport: function (params, success, failure) {
				const $request = $.ajax(params);

				$request
					.then(function (data, textStatus, jqXHR) {
						var totalPage = Number(
							jqXHR.getResponseHeader('x-wp-totalpages')
						);
						var paginationMore = currentPage < totalPage;

						var items = data.map((item) => {
							let text = item.name;

							if (!$addListing.length && params.data.search) {
								text = `${item.name} (${item.count})`;
							}

							return {
								id: item.id,
								text,
							};
						});

						return {
							items,
							paginationMore,
						};
					})
					.then(success);

				$request.fail(failure);

				return $request;
			},
		};
	}

	$el.length && $el.select2(select2Options);

	if (directorist.lazy_load_taxonomy_fields) {
		function setupSelectedItems($el, selectedId, selectedLabel) {
			if (!$el.length || !selectedId) {
				return;
			}

			const selectedIds = `${selectedId}`.split(',');
			const selectedLabels = selectedLabel
				? `${selectedLabel}`.split(',')
				: [];

			selectedIds.forEach((id, index) => {
				const label =
					selectedLabels.length >= index + 1
						? selectedLabels[index]
						: '';
				var option = new Option(label, id, true, true);

				$el.append(option);
				$el.trigger({
					type: 'select2:select',
					params: { data: { id: id, text: label } },
				});
			});
		}

		setupSelectedItems(
			$el,
			$el.data('selected-id'),
			$el.data('selected-label')
		);
	}
}
