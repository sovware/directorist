import debounce from '../../global/components/debounce';
import initSearchCategoryCustomFields from './category-custom-fields';

(function ($) {
	/** 
		Global Variables 
	*/

	// Globally accessible form_data
	let form_data = {};

	// Scrolling Pagination
	let scrollingPage = 1;
	let infinitePaginationIsLoading = false;
	let infinitePaginationCompleted = false;

	/** 
		Main Functions 
	*/

	// Perform Instant Search
	function performInstantSearch(searchElement) {
		// get parent element
		const searchElm = searchElement.closest('.directorist-instant-search');

		// Instant Search Data
		const instant_search_data = prepareInstantSearchData(searchElm);

		$.ajax({
			url: directorist.ajaxurl,
			type: 'POST',
			data: instant_search_data,
			beforeSend: function () {
				searchElm
					.find(
						'.directorist-advanced-filter__form .directorist-btn-sm'
					)
					.attr('disabled', true);
				searchElm
					.find('.directorist-archive-items')
					.addClass('atbdp-form-fade');
				searchElm
					.find(
						'.directorist-header-bar .directorist-advanced-filter'
					)
					.removeClass('directorist-advanced-filter--show')
					.hide();

				if (searchElm.offset()?.top > 0) {
					$(document).scrollTop(searchElm.offset().top);
				}

				closeAllSearchModal();
			},
			success: function (html) {
				if (html.search_result) {
					searchElm
						.find(
							'.directorist-header-found-title, .dsa-save-search-container'
						)
						.remove();
					if (html.header_title) {
						searchElm
							.find('.directorist-listings-header__left')
							.append(html.header_title);
						searchElm
							.find('.directorist-header-found-title span')
							.text(html.count);
					}
					searchElm
						.find('.directorist-archive-items')
						.replaceWith(html.search_result)
						.removeClass('atbdp-form-fade');
					searchElm
						.find(
							'.directorist-advanced-filter__form .directorist-btn-sm'
						)
						.attr('disabled', false);

					window.dispatchEvent(
						new CustomEvent('directorist-instant-search-reloaded')
					);
					window.dispatchEvent(
						new CustomEvent(
							'directorist-reload-listings-map-archive'
						)
					);

					// Optional: Update meta title
					let new_meta_title = '';
					if (html.category_name)
						new_meta_title += html.category_name;
					if (html.location_name)
						new_meta_title +=
							(new_meta_title ? ' within ' : '') +
							html.location_name;
					if (form_data.address)
						new_meta_title +=
							(form_data.in_cat || form_data.in_loc
								? ' near '
								: '') + form_data.address;
					document.title = new_meta_title
						? `${new_meta_title} | ${directorist.site_name}`
						: directorist.site_name;
				}

				// Initialize scrolling status
				scrollingPage = 1;
				infinitePaginationCompleted = false;
			},
		});
	}

	// Perform Instant Search for directory type change
	function onDirectoryChange(searchElement) {
		// get parent element
		const searchElm = searchElement.closest('.directorist-instant-search');

		// Instant Search Data
		const instant_search_data = prepareInstantSearchData(searchElm);

		$.ajax({
			url: directorist.ajaxurl,
			type: 'POST',
			data: instant_search_data,
			beforeSend: function () {
				searchElm.addClass('atbdp-form-fade');
			},
			success: function (html) {
				if (html.directory_type) {
					searchElm.replaceWith(html.directory_type);
					searchElm
						.find('.atbdp-form-fade')
						.removeClass('atbdp-form-fade');

					window.dispatchEvent(
						new CustomEvent('directorist-instant-search-reloaded')
					);
					window.dispatchEvent(
						new CustomEvent(
							'directorist-reload-listings-map-archive'
						)
					);

					// SearchForm Item in Single Category Location Page Init
					singleCategoryLocationInit();

					// Category Custom Field Assigned Init
					initSearchCategoryCustomFields($);
				}

				// Initialize scrolling status
				scrollingPage = 1;
				infinitePaginationCompleted = false;
			},
		});
	}

	// AJAX call to load more listings
	function loadMoreListings(searchElement) {
		let loadingDiv;
		const container = $(
			'.directorist-infinite-scroll .directorist-container-fluid .directorist-row'
		);

		// get parent element
		const searchElm = searchElement.closest('.directorist-instant-search');

		// Instant Search Data
		const preparedData = prepareInstantSearchData(searchElm);

		// make ajax data
		const instant_search_data = {
			...preparedData,
			paged: scrollingPage,
		};

		$.ajax({
			url: directorist.ajaxurl,
			type: 'POST',
			data: instant_search_data,
			beforeSend: function () {
				loadingDiv = $('<div>', {
					class: 'directorist-on-scroll-loading',
				}).append(
					$('<div>', { class: 'directorist-spinner' }),
					$('<span>').text('Loading more...')
				);
				container.append(loadingDiv);
			},
			success: function (html) {
				if (loadingDiv) loadingDiv.remove();

				if (html.count > 0) {
					container.append(html.render_listings);
				} else {
					infinitePaginationCompleted = true;
				}

				triggerCustomEvents();
			},
			complete: function () {
				infinitePaginationIsLoading = false;
				if (loadingDiv) loadingDiv.remove();
			},
		});
	}

	/**
    	Helper Functions  
  	**/

	// Prepare Instant Search Data
	function prepareInstantSearchData(searchElm) {
		// Get data-atts
		const instant_search_atts = searchElm.data('atts');

		// make ajax data
		const instant_search_data = {
			...form_data,
			action: 'directorist_instant_search',
			_nonce: directorist.ajax_nonce,
			current_page_id: directorist.current_page_id,
			data_atts: instant_search_atts,
		};

		return instant_search_data;
	}

	// Update or retain existing keys in form_data
	function updateFormData(newData) {
		Object.entries(newData).forEach(([key, value]) => {
			if (
				value === undefined ||
				value === null ||
				value === '' ||
				(Array.isArray(value) && value.length === 0) ||
				(typeof value === 'object' &&
					!Array.isArray(value) &&
					Object.keys(value).length === 0)
			) {
				delete form_data[key];
			} else {
				form_data[key] = value;
			}
		});
	}

	// Reset form_data
	function resetFormData() {
		Object.entries(form_data).forEach(([key, value]) => {
			delete form_data[key];
		});
	}

	// Update search URL with form data
	function update_instant_search_url(form_data) {
		if (!history.pushState) return;

		let newurl =
			window.location.protocol +
			'//' +
			window.location.host +
			window.location.pathname;
		let query = '';

		const appendQuery = (key, value) => {
			if (
				value !== undefined &&
				value !== null &&
				value !== '' &&
				(!Array.isArray(value) || value.length)
			) {
				if (Array.isArray(value) && value.length) {
					query += (query.length ? '&' : '?') + `${key}=${value}`;
				} else {
					query +=
						(query.length ? '&' : '?') +
						`${key}=${encodeURIComponent(value)}`;
				}
			}
		};

		// These keys will be ignored
		// and will not be appended to the URL
		// when updating the URL
		const ignoreKeys = [
			'data_atts',
			'custom_field',
			'current_page_id',
			'action',
			'_nonce',
		];

		// Handle all form_data keys dynamically
		Object.entries(form_data).forEach(([key, value]) => {
			if (ignoreKeys.includes(key)) return;

			// Handle default page
			if (key === 'paged' && Number(value) === 1) {
				return; // ❌ Skip default page 1
			}

			// Handle price & address fields specifically
			if (key === 'price' && Array.isArray(value)) {
				appendQuery('price[0]', value[0] > 0 ? value[0] : '');
				appendQuery('price[1]', value[1] > 0 ? value[1] : '');
			} else if (
				(key === 'cityLat' || key === 'cityLng') &&
				!form_data.address
			) {
				return; // ❌ Skip lat/lng if no address
			} else {
				appendQuery(key, value);
			}
		});

		// Handle custom_field
		if (
			form_data.custom_field &&
			typeof form_data.custom_field === 'object'
		) {
			Object.entries(form_data.custom_field).forEach(([key, val]) => {
				// Skip if key starts with "custom-number" and value is "0-0"
				if (key.startsWith('custom-number') && val === '0-0') {
					return;
				}

				appendQuery(key, val);
			});
		}

		const finalUrl = query ? newurl + query : newurl;
		window.history.pushState({ path: finalUrl }, '', finalUrl);
	}

	// Check required fields are valid or not
	function checkRequiredFields(searchElm) {
		// Select all required inputs and selects inside searchElm
		const requiredInputs = searchElm.find(
			'input[required], select[required], textarea[required]'
		);

		let requiredFieldsAreValid = true;

		requiredInputs.each(function () {
			const $el = $(this);
			const tagName = $el.prop('tagName').toLowerCase();
			const type = $el.attr('type');

			if (tagName === 'input') {
				if (type === 'checkbox' || type === 'radio') {
					// For checkboxes/radios, at least one with this name must be checked
					const name = $el.attr('name');
					const checked =
						searchElm.find(`input[name="${name}"]:checked`).length >
						0;
					if (!checked) {
						requiredFieldsAreValid = false;
						return false; // break .each loop early
					}
				} else {
					// For other input types, value must not be empty
					if (!$el.val()) {
						requiredFieldsAreValid = false;
						return false;
					}
				}
			} else if (tagName === 'select' || tagName === 'textarea') {
				// Select or textarea must have a value
				if (!$el.val()) {
					requiredFieldsAreValid = false;
					return false;
				}
			}
		});

		return requiredFieldsAreValid;
	}

	//  Build form_data from searchElm inputs.
	function buildFormData(searchElm) {
		let tag = [];
		let price = [];
		let custom_field = {};
		let search_by_rating = [];

		// Collect selected tags
		searchElm.find('input[name^="in_tag["]:checked').each((_, el) => {
			tag.push($(el).val());
		});

		// Collect selected ratings
		searchElm
			.find('input[name^="search_by_rating["]:checked')
			.each((_, el) => {
				search_by_rating.push($(el).val());
			});

		// Collect price values
		searchElm.find('input[name^="price["]').each((_, el) => {
			price.push($(el).val());
		});

		// Check if **any** price is greater than 0
		const hasValidPrice = price.some((val) => val > 0);

		if (!hasValidPrice) {
			price = []; // Reset price if no valid price found
		}

		// Collect custom field values
		searchElm.find('[name^="custom_field"]').each(function (_, el) {
			const $el = $(el);
			const name = $el.attr('name');
			const type = $el.attr('type');
			const match = name.match(/^custom_field\[(.+?)\]/);
			const post_id = match ? match[1] : '';

			if (!post_id) return;

			if (type === 'radio') {
				const checked = searchElm
					.find(`input[name="custom_field[${post_id}]"]:checked`)
					.val();
				if (checked) custom_field[post_id] = checked;
			} else if (type === 'checkbox') {
				const values = [];
				searchElm
					.find(`input[name="custom_field[${post_id}][]"]:checked`)
					.each(function () {
						const val = $(this).val();
						if (val) values.push(val);
					});
				if (values.length) custom_field[post_id] = values;
			} else {
				const value = $el.val();
				if (value && value !== '0-0') custom_field[post_id] = value;
			}
		});

		// Collect basic form values
		const q = searchElm.find('input[name="q"]').val();
		const in_cat = searchElm.find('.directorist-category-select').val();
		const in_loc = searchElm.find('.directorist-location-select').val();
		const price_range = searchElm
			.find("input[name='price_range']:checked")
			.val();
		const address = searchElm.find('input[name="address"]').val();
		const zip = searchElm.find('input[name="zip"]').val();
		const fax = searchElm.find('input[name="fax"]').val();
		const email = searchElm.find('input[name="email"]').val();
		const website = searchElm.find('input[name="website"]').val();
		const phone = searchElm.find('input[name="phone"]').val();
		const phone2 = searchElm.find('input[name="phone2"]').val();
		const view = form_data.view;
		const paged = form_data.paged;

		// Update form_data
		updateFormData({
			q,
			in_cat,
			in_loc,
			in_tag: tag,
			price,
			price_range,
			search_by_rating,
			address,
			zip,
			fax,
			email,
			website,
			phone,
			phone2,
			custom_field,
			view,
			paged,
		});

		// open_now checkbox
		const open_now_val = searchElm
			.find('input[name="open_now"]')
			.is(':checked')
			? searchElm.find('input[name="open_now"]').val()
			: undefined;
		updateFormData({ open_now: open_now_val });

		const radius_search_based_on = searchElm
			.find('.directorist-radius_search_based_on')
			.val();

		// Check if the address or zip code is present to update miles, lat, and lng
		if (radius_search_based_on === 'address' && address) {
			updateFormData({
				cityLat: searchElm.find('#cityLat').val(),
				cityLng: searchElm.find('#cityLng').val(),
				miles: searchElm.find('input[name="miles"]').val(),
			});
		} else if (radius_search_based_on === 'zip' && zip) {
			updateFormData({
				zip_cityLat: searchElm.find('.zip-cityLat').val(),
				zip_cityLng: searchElm.find('.zip-cityLng').val(),
				miles: searchElm.find('input[name="miles"]').val(),
			});
		} else {
			updateFormData({
				cityLat: undefined,
				cityLng: undefined,
				zip_cityLat: undefined,
				zip_cityLng: undefined,
				miles: undefined,
			});
		}

		// Paging: get current page number, default 1 if not found
		let page = parseInt(form_data.paged, 10) || 1;
		updateFormData({
			paged: page > 1 ? page : undefined,
		});

		// Update URL with form data
		update_instant_search_url(form_data);
	}

	// Build form data without required value
	function buildFormDataWithoutRequired() {
		const notRequiredFields = ['view', 'sort', 'paged'];

		Object.entries(form_data).forEach(([key, value]) => {
			if (!notRequiredFields.includes(key)) {
				delete form_data[key];
			}
		});

		// Update URL with form data
		update_instant_search_url(form_data);
	}

	// Perform Instant Search with required value
	function performInstantSearchWithRequiredValue(searchElm) {
		// Build form data
		buildFormData(searchElm);

		// Check required fields
		const allRequiredFieldsAreValid = checkRequiredFields(searchElm);

		// If required fields are valid, proceed with filtering
		if (allRequiredFieldsAreValid) {
			performInstantSearch(searchElm);
		}
	}

	// Perform Instant Search without required value
	function performInstantSearchWithoutRequiredValue(searchElm) {
		// Check required fields
		const allRequiredFieldsAreValid = checkRequiredFields(searchElm);

		// If required fields are valid, proceed with filtering
		if (allRequiredFieldsAreValid) {
			// Build form data
			buildFormData(searchElm);

			performInstantSearch(searchElm);
		} else {
			// Build form data without required value
			buildFormDataWithoutRequired();

			// Filter Listing
			performInstantSearch(searchElm);
		}
	}

	// Handle Infinite Scroll
	function handleScroll() {
		const container = $(
			'.directorist-infinite-scroll .directorist-container-fluid .directorist-row'
		);
		if (!container.length || infinitePaginationIsLoading) {
			return;
		}

		const containerBottom =
			container.offset().top + container.outerHeight();
		const scrollBottom = window.scrollY + window.innerHeight;

		if (scrollBottom >= containerBottom) {
			infinitePaginationIsLoading = true;
			scrollingPage++;

			// get parent element
			const instantSearchElement = $('.directorist-instant-search');
			// get active form
			const activeForm = getActiveForm(instantSearchElement);

			// build form_data
			buildFormData(activeForm);

			// Load more listings
			loadMoreListings(activeForm);
		}
	}

	// Close all search modal
	function closeAllSearchModal() {
		var searchModalElement = document.querySelectorAll(
			'.directorist-search-modal'
		);

		searchModalElement.forEach((modal) => {
			var modalOverlay = modal.querySelector(
				'.directorist-search-modal__overlay'
			);
			var modalContent = modal.querySelector(
				'.directorist-search-modal__contents'
			);
			var modalBodyOverlay = document.querySelector(
				'.directorist-content-active'
			);

			// Overlay Style
			if (modalOverlay) {
				modalOverlay.style.cssText =
					'opacity: 0; visibility: hidden; transition: 0.5s ease';
				// remove overlay class on body
				modalBodyOverlay.classList.remove('directorist-overlay-active');
			}

			// Modal Content Style
			if (modalContent) {
				modalContent.style.cssText =
					'opacity: 0; visibility: hidden; bottom: -200px;';
			}
		});
	}

	// Determine the active form
	function getActiveForm(instantSearchElement) {
		const sidebarListing = instantSearchElement.find(
			'.listing-with-sidebar'
		);
		const advancedForm = instantSearchElement.find(
			'.directorist-advanced-filter__form'
		);
		const searchForm = instantSearchElement.find(
			'.directorist-search-form'
		);
		return sidebarListing.length
			? instantSearchElement
			: screen.width > 575
				? advancedForm
				: searchForm;
	}

	// Get directory type
	function getDirectoryType(directoryTypeLink) {
		const typeMatch = directoryTypeLink.attr('href')?.match(/type=([^&]+)/);
		return typeMatch ? typeMatch[1] : '';
	}

	// Get view as
	function getViewAs(viewAsLink) {
		const viewMatch = viewAsLink.attr('href')?.match(/view=([^&]+)/);
		return viewMatch ? viewMatch[1] : '';
	}

	// Get sort value
	function getSortValue(sortByLink) {
		let sort_href = sortByLink.attr('data-link');
		let sort_by =
			sort_href && sort_href.length ? sort_href.match(/sort=.+/) : '';
		return sort_by && sort_by.length ? sort_by[0].replace(/sort=/, '') : '';
	}

	// Trigger custom events
	function triggerCustomEvents() {
		window.dispatchEvent(new Event('directorist-instant-search-reloaded'));
		window.dispatchEvent(
			new Event('directorist-reload-listings-map-archive')
		);
	}

	// Range Slider searching observer
	function initObserver() {
		let targetNodes = document.querySelectorAll(
			'.directorist-instant-search .directorist-custom-range-slider__value input'
		);

		targetNodes.forEach((targetNode) => {
			let searchElm = $(targetNode.closest('form'));

			if (targetNode) {
				let timeout;
				const observerCallback = (mutationList, observer) => {
					for (const mutation of mutationList) {
						if (mutation.attributeName == 'value') {
							clearTimeout(timeout);
							timeout = setTimeout(() => {
								// Instant search with required value
								performInstantSearchWithRequiredValue(
									searchElm
								);
							}, 250);
						}
					}
				};

				const observer = new MutationObserver(observerCallback);
				observer.observe(targetNode, {
					attributes: true,
					childList: true,
					subtree: true,
				});
			}
		});
	}

	// Single Location Category Page Search Form Item Disable
	function singleCategoryLocationInit() {
		const directoristArchiveContents = document.querySelector(
			'.directorist-archive-contents'
		);
		if (!directoristArchiveContents) {
			return;
		}

		const directoristDataAttributes =
			directoristArchiveContents.getAttribute('data-atts');
		const { shortcode, location, category } = JSON.parse(
			directoristDataAttributes
		);

		if (shortcode === 'directorist_category' && category.trim() !== '') {
			const categorySelect = document.querySelector(
				'.directorist-search-form .directorist-category-select'
			);
			if (categorySelect) {
				categorySelect
					.closest('.directorist-search-category')
					.classList.add('directorist-search-form__single-category');
			}
		}

		if (shortcode === 'directorist_location' && location.trim() !== '') {
			const locationSelect = document.querySelector(
				'.directorist-search-form .directorist-location-select'
			);
			if (locationSelect) {
				locationSelect
					.closest('.directorist-search-location')
					.classList.add('directorist-search-form__single-location');
			}
		}
	}

	/** 
		Event Listeners 
	*/

	// sidebar on keyup searching
	$('body').on(
		'keyup',
		'.directorist-instant-search .listing-with-sidebar form',
		debounce(function (e) {
			if (
				$(e.target).closest('.directorist-custom-range-slider__value')
					.length > 0
			) {
				return; // Skip search for this element
			}

			e.preventDefault();
			var searchElm = $(this).closest('.listing-with-sidebar');

			// Instant search with required value
			performInstantSearchWithRequiredValue(searchElm);
		}, 250)
	);

	// sidebar on change searching - radio/checkbox/location/range
	$('body').on(
		'change',
		".directorist-instant-search .listing-with-sidebar input[type='checkbox'],.directorist-instant-search .listing-with-sidebar input[type='radio'], .directorist-instant-search .listing-with-sidebar input[type='time'], .directorist-instant-search .listing-with-sidebar input[type='date'], .directorist-instant-search .listing-with-sidebar .directorist-custom-range-slider__wrap .directorist-custom-range-slider__range, .directorist-instant-search .listing-with-sidebar .directorist-search-location .location-name",
		debounce(function (e) {
			e.preventDefault();
			var searchElm = $(this).closest('.listing-with-sidebar');

			// Instant search with required value
			performInstantSearchWithRequiredValue(searchElm);
		}, 250)
	);

	// sidebar on change searching - zipcode/location
	$('body').on(
		'change',
		'.directorist-instant-search .listing-with-sidebar .directorist-search-location, .directorist-instant-search .listing-with-sidebar .directorist-zipcode-search',
		debounce(function (e) {
			e.preventDefault();
			const searchElm = $(this).closest('.listing-with-sidebar');

			// If it's a location field, ensure it has a value before triggering the filter
			if ($(this).hasClass('directorist-search-location')) {
				const locationField = $(this).find('input[name="address"]');
				if (!locationField.val()) {
					return;
				}
			}

			// Instant search with required value
			performInstantSearchWithRequiredValue(searchElm);
		}, 250)
	);

	// sidebar on change searching - select
	$('body').on(
		'change',
		'.directorist-instant-search .listing-with-sidebar select',
		debounce(function (e) {
			e.preventDefault();
			if (!$(this).val()) {
				return; // Skip search if the value is empty
			}

			e.preventDefault();
			var searchElm =
				$(this).val() && $(this).closest('.listing-with-sidebar');

			// Instant search with required value
			performInstantSearchWithRequiredValue(searchElm);
		}, 250)
	);

	// sidebar on change searching - color
	window.addEventListener(
		'directorist-color-changed',
		debounce(function (e) {
			const { input } = e.detail;
			const searchElm = $(input).closest('.listing-with-sidebar');

			if (!searchElm.length) return;

			// Instant search with required value
			performInstantSearchWithRequiredValue(searchElm);
		}, 250)
	);

	// sidebar on click searching - location icon
	$('body').on(
		'click',
		'.directorist-instant-search .listing-with-sidebar .directorist-filter-location-icon',
		debounce(function (e) {
			e.preventDefault();
			var searchElm = $(this).closest('.listing-with-sidebar');

			// Instant search with required value
			performInstantSearchWithRequiredValue(searchElm);
		}, 1000)
	);

	// Clear Input Value
	$('body').on(
		'click',
		'.directorist-instant-search .listing-with-sidebar .directorist-search-field__btn--clear',
		function (e) {
			// Clear Color Field Value
			let irisPicker = $(this)
				.closest('.directorist-search-field.directorist-color')
				.find('input.wp-picker-clear');

			if (irisPicker !== null) {
				irisPicker.click();
			}

			let $searchField = $(this).closest('.directorist-search-field');
			let $form = $(
				document.querySelector(
					'.directorist-instant-search .listing-with-sidebar form'
				)
			);

			// Clear text, email, number, select fields etc
			$searchField
				.find(
					'input:not([type="checkbox"]):not([type="radio"]):not(.wp-picker-clear), select'
				)
				.val('');

			// Clear checkboxes
			$searchField.find('input[type="checkbox"]').prop('checked', false);

			// Clear radio buttons
			$searchField.find('input[type="radio"]').prop('checked', false);

			// Proceed if form exists
			if ($form.length) {
				performInstantSearchWithRequiredValue($form);
			}
		}
	);

	// Directorist instant search reset
	$('body').on(
		'click',
		'.directorist-instant-search .listing-with-sidebar  .directorist-btn-reset-js',
		function (e) {
			e.preventDefault();
			let searchElm = $(this).closest('.directorist-instant-search');
			// Get active form
			const activeForm = getActiveForm(searchElm);

			// ✅ only update `page`, preserve others
			updateFormData({ paged: 1 });

			// Build form data
			buildFormData(activeForm);

			// Filter Listing
			debounce(function (e) {
				performInstantSearch(activeForm);
			}, 250);
		}
	);

	// Directorist instant search submit
	$('body').on('submit', '.directorist-instant-search form', function (e) {
		e.preventDefault();
		let _this = $(this);

		// Instant search with required value
		performInstantSearchWithRequiredValue(_this);
	});

	// Directorist instant search submit - for advanced filter
	$('body').on(
		'submit',
		'.widget .default-ad-search:not(.directorist_single) .directorist-advanced-filter__form',
		function (e) {
			if ($('.directorist-instant-search').length) {
				e.preventDefault();
				let _this = $(this);

				// Instant search with required value
				performInstantSearchWithRequiredValue(_this);
			}
		}
	);

	// Directorist type changes
	$('body').on(
		'click',
		'.directorist-instant-search .directorist-type-nav__link',
		function (e) {
			e.preventDefault();

			// Check if the clicked item is already active
			if (
				$(this)
					.closest('.directorist-type-nav__list li')
					.hasClass('directorist-type-nav__list__current')
			) {
				return; // Skip if already active
			}

			// get parent element
			let searchElm = $(this).closest('.directorist-instant-search');

			// reset form data
			resetFormData();

			// get directory_type
			const directory_type = getDirectoryType($(this));
			// ✅ only update `directory_type`, preserve others
			updateFormData({ directory_type });

			// Update URL with form data
			update_instant_search_url(form_data);

			// Get active form
			const activeForm = getActiveForm(searchElm);

			// Instant search for directory type change
			onDirectoryChange(activeForm);
		}
	);

	// Directorist view as changes
	$('body').on(
		'click',
		'.directorist-instant-search .directorist-viewas .directorist-viewas__item',
		function (e) {
			e.preventDefault();

			// Check if the clicked item is already active
			if ($(this).hasClass('active')) {
				return; // Skip if already active
			}

			// get parent element
			let searchElm = $(this).closest('.directorist-instant-search');

			// get view as value
			const view = getViewAs($(this));
			// ✅ only update `view`, preserve others
			updateFormData({ view });

			// Get active form
			const activeForm = getActiveForm(searchElm);

			// Instant search without required value
			performInstantSearchWithoutRequiredValue(activeForm);
		}
	);

	// Directorist sort by changes
	$('body').on(
		'click',
		'.directorist-instant-search .directorist-sortby-dropdown .directorist-dropdown__links__single-js',
		function (e) {
			e.preventDefault();

			// toggle active class
			$(this)
				.addClass('active')
				.siblings('.directorist-dropdown__links__single-js')
				.removeClass('active');

			// get parent element
			let searchElm = $(this).closest('.directorist-instant-search');

			// get sort value
			const sort = getSortValue($(this));
			// ✅ only update `sort`, preserve others
			updateFormData({ sort });

			// get active form
			const activeForm = getActiveForm(searchElm);

			// Instant search without required value
			performInstantSearchWithoutRequiredValue(activeForm);
		}
	);

	// Directorist pagination changes
	$('body').on(
		'click',
		'.directorist-instant-search .directorist-pagination .page-numbers',
		function (e) {
			e.preventDefault();
			let page = form_data.paged || 1;
			const currentPage = $(this).text();
			if (currentPage) {
				page = parseInt(currentPage);
			} else if ($(this).hasClass('next')) {
				page = parseInt(page) + 1;
			} else if ($(this).hasClass('prev')) {
				page = parseInt(page) - 1;
			}
			// ✅ only update `sort`, preserve others
			updateFormData({ paged: page });

			// get parent element
			let searchElm = $(this).closest('.directorist-instant-search');

			// get active form
			const activeForm = getActiveForm(searchElm);

			// Instant search without required value
			performInstantSearchWithoutRequiredValue(activeForm);
		}
	);

	// Submit on sidebar form
	if ($('.directorist-instant-search').length === 0) {
		$('body').on(
			'submit',
			'.listing-with-sidebar .directorist-basic-search, .listing-with-sidebar .directorist-advanced-search',
			function (e) {
				e.preventDefault();
				let basic_data = $(
					'.listing-with-sidebar .directorist-basic-search'
				).serialize();
				let advanced_data = $(
					'.listing-with-sidebar .directorist-advanced-search'
				).serialize();
				let action_value = $('.directorist-advanced-search').attr(
					'action'
				);
				let url = action_value + '?' + basic_data + '&' + advanced_data;

				window.location.href = url;
			}
		);
	}

	// Prevent disabled links from being clicked
	$('body').on('click', '.disabled-link', function (e) {
		e.preventDefault();
	});

	// Prevent default action for dropdown links
	$(
		'.directorist-instant-search .directorist-dropdown__links__single-js'
	).off('click');

	// Initialize Infinite Scroll
	window.addEventListener('scroll', function () {
		if (infinitePaginationCompleted) {
			scrollingPage = 1;
			return;
		}

		handleScroll();
	});

	// Initialize the observer for single category location
	window.addEventListener('load', function () {
		debounce(initObserver(), 250);

		singleCategoryLocationInit();
	});
})(jQuery);
