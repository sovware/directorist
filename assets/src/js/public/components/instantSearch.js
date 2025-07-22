import debounce from '../../global/components/debounce';
<<<<<<< HEAD
( function ( $ ) {
	let full_url = window.location.href;

	// Update search URL with form data
	function update_instant_search_url( form_data ) {
		if ( history.pushState ) {
			var newurl =
				window.location.protocol +
				'//' +
				window.location.host +
				window.location.pathname;

			if ( form_data.paged && form_data.paged.length ) {
				var query =
					query && query.length
						? query + '&paged=' + form_data.paged
						: '?paged=' + form_data.paged;
			}
			if ( form_data.directory_type && form_data.directory_type.length ) {
				var query =
					query && query.length
						? query + '&directory_type=' + form_data.directory_type
						: '?directory_type=' + form_data.directory_type;
			}
			if ( form_data.view && form_data.view.length ) {
				var query =
					query && query.length
						? query + '&view=' + form_data.view
						: '?view=' + form_data.view;
			}
			if ( form_data.q && form_data.q.length ) {
				var query =
					query && query.length
						? query + '&q=' + form_data.q
						: '?q=' + form_data.q;
			}
			if ( form_data.in_cat && form_data.in_cat.length ) {
				var query =
					query && query.length
						? query + '&in_cat=' + form_data.in_cat
						: '?in_cat=' + form_data.in_cat;
			}
			if ( form_data.in_loc && form_data.in_loc.length ) {
				var query =
					query && query.length
						? query + '&in_loc=' + form_data.in_loc
						: '?in_loc=' + form_data.in_loc;
			}
			if ( form_data.in_tag && form_data.in_tag.length ) {
				var query =
					query && query.length
						? query + '&in_tag=' + form_data.in_tag
						: '?in_tag=' + form_data.in_tag;
			}
			if (
				form_data.price &&
				form_data.price[ 0 ] &&
				form_data.price[ 0 ] > 0
			) {
				var query =
					query && query.length
						? query + '&price%5B0%5D=' + form_data.price[ 0 ]
						: '?price%5B0%5D=' + form_data.price[ 0 ];
			}
			if (
				form_data.price &&
				form_data.price[ 1 ] &&
				form_data.price[ 1 ] > 0
			) {
				var query =
					query && query.length
						? query + '&price%5B1%5D=' + form_data.price[ 1 ]
						: '?price%5B1%5D=' + form_data.price[ 1 ];
			}
			if ( form_data.price_range && form_data.price_range.length ) {
				var query =
					query && query.length
						? query + '&price_range=' + form_data.price_range
						: '?price_range=' + form_data.price_range;
			}
			if (
				form_data.search_by_rating &&
				form_data.search_by_rating.length
			) {
				var query =
					query && query.length
						? query +
						  '&search_by_rating=' +
						  form_data.search_by_rating
						: '?search_by_rating=' + form_data.search_by_rating;
			}
			if (
				form_data.cityLat &&
				form_data.cityLat.length &&
				form_data.address &&
				form_data.address.length
			) {
				var query =
					query && query.length
						? query + '&cityLat=' + form_data.cityLat
						: '?cityLat=' + form_data.cityLat;
			}
			if (
				form_data.cityLng &&
				form_data.cityLng.length &&
				form_data.address &&
				form_data.address.length
			) {
				var query =
					query && query.length
						? query + '&cityLng=' + form_data.cityLng
						: '?cityLng=' + form_data.cityLng;
			}
			if ( form_data.miles && form_data.miles.length ) {
				var query =
					query && query.length
						? query + '&miles=' + form_data.miles
						: '?miles=' + form_data.miles;
			}
			if ( form_data.address && form_data.address.length ) {
				var query =
					query && query.length
						? query + '&address=' + form_data.address
						: '?address=' + form_data.address;
			}
			if ( form_data.zip && form_data.zip.length ) {
				var query =
					query && query.length
						? query + '&zip=' + form_data.zip
						: '?zip=' + form_data.zip;
			}
			if ( form_data.fax && form_data.fax.length ) {
				var query =
					query && query.length
						? query + '&fax=' + form_data.fax
						: '?fax=' + form_data.fax;
			}
			if ( form_data.email && form_data.email.length ) {
				var query =
					query && query.length
						? query + '&email=' + form_data.email
						: '?email=' + form_data.email;
			}
			if ( form_data.website && form_data.website.length ) {
				var query =
					query && query.length
						? query + '&website=' + form_data.website
						: '?website=' + form_data.website;
			}
			if ( form_data.phone && form_data.phone.length ) {
				var query =
					query && query.length
						? query + '&phone=' + form_data.phone
						: '?phone=' + form_data.phone;
			}
			if (
				form_data.custom_field &&
				Object.keys( form_data.custom_field ).length
			) {
				Object.keys( form_data.custom_field ).forEach( ( key ) => {
					query = query.length
						? query + `&${ key }=${ form_data.custom_field[ key ] }`
						: `?${ key }=${ form_data.custom_field[ key ] }`;
				} );
			}
			if ( form_data.open_now && form_data.open_now.length ) {
				var query =
					query && query.length
						? query + '&open_now=' + form_data.open_now
						: '?open_now=' + form_data.open_now;
=======
import initSearchCategoryCustomFields from "./category-custom-fields";

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
>>>>>>> development
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

<<<<<<< HEAD
	// Get URL Parameter
	function getURLParameter( url, name ) {
		var regex = new RegExp( '[?&]' + name + '(=([^&#]*)|&|#|$)' );
		var results = regex.exec( url );
		if ( ! results || ! results[ 2 ] ) {
			return '';
		}

		return decodeURIComponent( results[ 2 ] );
=======
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
>>>>>>> development
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

		searchModalElement.forEach( ( modal ) => {
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
			if ( modalOverlay ) {
				modalOverlay.style.cssText =
					'opacity: 0; visibility: hidden; transition: 0.5s ease';
				// remove overlay class on body
				modalBodyOverlay.classList.remove(
					'directorist-overlay-active'
				);
			}

			// Modal Content Style
			if ( modalContent ) {
				modalContent.style.cssText =
					'opacity: 0; visibility: hidden; bottom: -200px;';
			}
		} );
	}

<<<<<<< HEAD
	// Scrolling Pagination
	let page = 1;
	let infinitePaginationIsLoading = false;
	let infinitePaginationCompleted = false;

	function handleScroll() {
		const container = $(
			'.directorist-infinite-scroll .directorist-container-fluid .directorist-row'
		);
		if ( ! container.length || infinitePaginationIsLoading ) return;

		const containerBottom =
			container.offset().top + container.outerHeight();
		const scrollBottom = window.scrollY + window.innerHeight;

		if ( scrollBottom >= containerBottom ) {
			infinitePaginationIsLoading = true;
			page++;

			const instantSearchElement = $( '.directorist-instant-search' );
			const activeForm = getActiveForm( instantSearchElement );
			const formData = buildFormData( activeForm, instantSearchElement );

			loadMoreListings( formData );
		}
	}

	window.addEventListener( 'scroll', function () {
		if ( infinitePaginationCompleted ) return;
		handleScroll();
	} );

	/* Directorist instant search */
	$( 'body' ).on(
		'submit',
		'.directorist-instant-search form',
		function ( e ) {
			e.preventDefault();
			// infinite pagination loading reset
			page = 1;
			infinitePaginationIsLoading = false;
			infinitePaginationCompleted = false;

			let instant_search_element = $( this ).closest(
				'.directorist-instant-search'
			);
			let tag = [];
			let search_by_rating = [];
			let price = [];
			let custom_field = {};

			$( this )
				.find( 'input[name^="in_tag["]:checked' )
				.each( function ( index, el ) {
					tag.push( $( el ).val() );
				} );

			$( this )
				.find( 'input[name^="search_by_rating["]:checked' )
				.each( function ( index, el ) {
					search_by_rating.push( $( el ).val() );
				} );

			$( this )
				.find( 'input[name^="price["]' )
				.each( function ( index, el ) {
					price.push( $( el ).val() );
				} );

			$( this )
				.find( '[name^="custom_field"]' )
				.each( function ( index, el ) {
					var name = $( el ).attr( 'name' );
					var type = $( el ).attr( 'type' );
					var post_id = name
						.replace( /(custom_field\[)/, '' )
						.replace( /\]/, '' );
					if ( 'radio' === type ) {
						$.each(
							$(
								"input[name='custom_field[" +
									post_id +
									"]']:checked"
							),
							function () {
								value = $( this ).val();
								custom_field[ post_id ] = value;
							}
						);
					} else if ( 'checkbox' === type ) {
						post_id = post_id.split( '[]' )[ 0 ];
						if ( ! custom_field[ post_id ] ) {
							custom_field[ post_id ] = [];
						}
						$.each(
							$(
								"input[name='custom_field[" +
									post_id +
									"][]']:checked"
							),
							function () {
								var value = $( this ).val();
								custom_field[ post_id ].push( value );
							}
						);
					} else {
						var value = $( el ).val();
						custom_field[ post_id ] = value;
					}
				} );

			let view_href = instant_search_element
				.find( '.directorist-viewas .directorist-viewas__item.active' )
				.attr( 'href' );
			let view_as =
				view_href && view_href.length
					? view_href.match( /view=.+/ )
					: '';
			let view =
				view_as && view_as.length
					? view_as[ 0 ].replace( /view=/, '' )
					: '';
			let type_href = instant_search_element
				.find(
					'.directorist-type-nav__list .directorist-type-nav__list__current a'
				)
				.attr( 'href' );
			let type =
				type_href && type_href.length
					? type_href.match( /directory_type=.+/ )
					: '';
			let directory_type = getURLParameter( type_href, 'directory_type' );
			let data_atts = instant_search_element.attr( 'data-atts' );

			var data = {
				action: 'directorist_instant_search',
				_nonce: directorist.ajax_nonce,
				current_page_id: directorist.current_page_id,
				in_tag: tag,
				price: price,
				search_by_rating: search_by_rating,
				custom_field: custom_field,
				data_atts: JSON.parse( data_atts ),
			};

			var fields = {
				q: $( this ).find( 'input[name="q"]' ).val(),
				in_cat: $( this ).find( '.directorist-category-select' ).val(),
				in_loc: $( this ).find( '.directorist-location-select' ).val(),
				price_range: $( this )
					.find( "input[name='price_range']:checked" )
					.val(),
				address: $( this ).find( 'input[name="address"]' ).val(),
				zip: $( this ).find( 'input[name="zip"]' ).val(),
				fax: $( this ).find( 'input[name="fax"]' ).val(),
				email: $( this ).find( 'input[name="email"]' ).val(),
				website: $( this ).find( 'input[name="website"]' ).val(),
				phone: $( this ).find( 'input[name="phone"]' ).val(),
			};

			//business hours
			if ( $( 'input[name="open_now"]' ).is( ':checked' ) ) {
				fields.open_now = $( this )
					.find( 'input[name="open_now"]' )
					.val();
			}

			if ( fields.address && fields.address.length ) {
				fields.cityLat = $( this ).find( '#cityLat' ).val();
				fields.cityLng = $( this ).find( '#cityLng' ).val();
				fields.miles = $( this ).find( 'input[name="miles"]' ).val();
			}

			if ( fields.zip && fields.zip.length ) {
				fields.zip_cityLat = $( this ).find( '.zip-cityLat' ).val();
				fields.zip_cityLng = $( this ).find( '.zip-cityLng' ).val();
				fields.miles = $( this ).find( 'input[name="miles"]' ).val();
			}

			var form_data = {
				...data,
				...fields,
			};

			const allFieldsAreEmpty = Object.values( fields ).every(
				( item ) => ! item
			);
			const tagFieldEmpty = data.in_tag.every( ( item ) => ! item );
			const priceFieldEmpty = data.price.every( ( item ) => ! item );
			const ratingFieldEmpty = data.search_by_rating.every(
				( item ) => ! item
			);
			const customFieldsAreEmpty = Object.values(
				data.custom_field
			).every( ( item ) => ! item );

			if (
				! allFieldsAreEmpty ||
				! tagFieldEmpty ||
				! priceFieldEmpty ||
				! customFieldsAreEmpty ||
				! ratingFieldEmpty
			) {
				if ( view && view.length ) {
					form_data.view = view;
				}

				if ( directory_type && directory_type.length ) {
					form_data.directory_type = directory_type;
				}

				update_instant_search_url( form_data );

				$.ajax( {
					url: directorist.ajaxurl,
					type: 'POST',
					data: form_data,
					beforeSend: function () {
						instant_search_element
							.find(
								'.directorist-advanced-filter__form .directorist-btn-sm'
							)
							.attr( 'disabled', true );
						instant_search_element
							.find( '.directorist-archive-items' )
							.addClass( 'atbdp-form-fade' );
						instant_search_element
							.find(
								'.directorist-header-bar .directorist-advanced-filter'
							)
							.removeClass( 'directorist-advanced-filter--show' );
						instant_search_element
							.find(
								'.directorist-header-bar .directorist-advanced-filter'
							)
							.hide();
						$( document ).scrollTop(
							instant_search_element.offset().top
						);
						closeAllSearchModal();
					},
					success: function ( html ) {
						if ( html.search_result ) {
							instant_search_element
								.find( '.directorist-header-found-title' )
								.remove();
							instant_search_element
								.find( '.dsa-save-search-container' )
								.remove();
							instant_search_element
								.find( '.directorist-listings-header__left' )
								.append( html.header_title );
							instant_search_element
								.find( '.directorist-header-found-title span' )
								.text( html.count );
							instant_search_element
								.find( '.directorist-archive-items' )
								.replaceWith( html.search_result );
							instant_search_element
								.find( '.directorist-archive-items' )
								.removeClass( 'atbdp-form-fade' );
							instant_search_element
								.find(
									'.directorist-advanced-filter__form .directorist-btn-sm'
								)
								.attr( 'disabled', false );
							window.dispatchEvent(
								new CustomEvent(
									'directorist-instant-search-reloaded'
								)
							);
							window.dispatchEvent(
								new CustomEvent(
									'directorist-reload-listings-map-archive'
								)
							);

							var website_name = directorist.site_name; // This is dynamically set from WordPress

							// Construct the new meta title
							var new_meta_title = ''; // Start with an empty title
							// Check if the category is selected and append to the title
							if ( String( html.category_name ) ) {
								new_meta_title += html.category_name;
							}

							// Check if location is selected and append with proper formatting
							if ( String( html.location_name ) ) {
								if ( String( html.category_name ) ) {
									new_meta_title +=
										' within ' + html.location_name; // If category exists, add with a comma
								} else {
									new_meta_title += html.location_name; // If no category, just add location
								}
							}

							// Check if address is selected and append with proper formatting
							if ( fields.address ) {
								if ( fields.in_cat || fields.in_loc ) {
									new_meta_title += ' near ' + fields.address; // If category or location exists, add "near"
								} else {
									new_meta_title += fields.address; // Default to just the address
								}
							}

							// Append website name to the meta title with a pipe separator
							if ( new_meta_title ) {
								new_meta_title += ' | ' + website_name; // Append the website name only if the title has content
							} else {
								new_meta_title = website_name; // Default to only the website name if no other title parts are present
							}

							// Update the meta title dynamically
							document.title = new_meta_title;
						}
					},
				} );
			}
		}
	);

	/* Directorist instant reset */
	$( 'body' ).on(
		'click',
		'.directorist-instant-search .directorist-btn-reset-js',
		function ( e ) {
			e.preventDefault();
			let instant_search_element = $( this ).closest(
				'.directorist-instant-search'
			);
			let tag = [];
			let search_by_rating = [];
			let price = [];
			let custom_field = {};

			$( this )
				.find( 'input[name^="in_tag["]:checked' )
				.each( function ( index, el ) {
					tag.push( $( el ).val() );
				} );

			$( this )
				.find( 'input[name^="search_by_rating["]:checked' )
				.each( function ( index, el ) {
					search_by_rating.push( $( el ).val() );
				} );

			$( this )
				.find( 'input[name^="price["]' )
				.each( function ( index, el ) {
					price.push( $( el ).val() );
				} );

			$( this )
				.find( '[name^="custom_field"]' )
				.each( function ( index, el ) {
					var name = $( el ).attr( 'name' );
					var type = $( el ).attr( 'type' );
					var post_id = name
						.replace( /(custom_field\[)/, '' )
						.replace( /\]/, '' );
					if ( 'radio' === type ) {
						$.each(
							$(
								"input[name='custom_field[" +
									post_id +
									"]']:checked"
							),
							function () {
								value = $( this ).val();
								custom_field[ post_id ] = value;
							}
						);
					} else if ( 'checkbox' === type ) {
						post_id = post_id.split( '[]' )[ 0 ];
						$.each(
							$(
								"input[name='custom_field[" +
									post_id +
									"][]']:checked"
							),
							function () {
								var checkValue = [];
								value = $( this ).val();
								checkValue.push( value );
								custom_field[ post_id ] = checkValue;
							}
						);
					} else {
						var value = $( el ).val();
						custom_field[ post_id ] = value;
					}
				} );

			let view_href = instant_search_element
				.find( '.directorist-viewas .directorist-viewas__item.active' )
				.attr( 'href' );
			let view_as =
				view_href && view_href.length
					? view_href.match( /view=.+/ )
					: '';
			let view =
				view_as && view_as.length
					? view_as[ 0 ].replace( /view=/, '' )
					: '';
			let type_href = instant_search_element
				.find(
					'.directorist-type-nav__list .directorist-type-nav__list__current a'
				)
				.attr( 'href' );
			let type =
				type_href && type_href.length
					? type_href.match( /directory_type=.+/ )
					: '';
			let directory_type = getURLParameter( type_href, 'directory_type' );
			let data_atts = instant_search_element.attr( 'data-atts' );

			var data = {
				action: 'directorist_instant_search',
				_nonce: directorist.ajax_nonce,
				current_page_id: directorist.current_page_id,
				data_atts: JSON.parse( data_atts ),
			};

			var form_data = {
				...data,
			};

			if ( view && view.length ) {
				form_data.view = view;
			}

			if ( directory_type && directory_type.length ) {
				form_data.directory_type = directory_type;
			}

			update_instant_search_url( form_data );

			$.ajax( {
				url: directorist.ajaxurl,
				type: 'POST',
				data: form_data,
				beforeSend: function () {
					instant_search_element
						.find(
							'.directorist-advanced-filter__form .directorist-btn-sm'
						)
						.attr( 'disabled', true );
					instant_search_element
						.find( '.directorist-archive-items' )
						.addClass( 'atbdp-form-fade' );
					instant_search_element
						.find(
							'.directorist-header-bar .directorist-advanced-filter'
						)
						.removeClass( 'directorist-advanced-filter--show' );
					instant_search_element
						.find(
							'.directorist-header-bar .directorist-advanced-filter'
						)
						.hide();
					$( document ).scrollTop(
						instant_search_element.offset().top
					);
				},
				success: function ( html ) {
					if ( html.search_result ) {
						instant_search_element
							.find( '.directorist-header-found-title span' )
							.text( html.count );
						instant_search_element
							.find( '.directorist-archive-items' )
							.replaceWith( html.search_result );
						instant_search_element
							.find( '.directorist-archive-items' )
							.removeClass( 'atbdp-form-fade' );
						instant_search_element
							.find(
								'.directorist-advanced-filter__form .directorist-btn-sm'
							)
							.attr( 'disabled', false );
						window.dispatchEvent(
							new CustomEvent(
								'directorist-instant-search-reloaded'
							)
						);
						window.dispatchEvent(
							new CustomEvent(
								'directorist-reload-listings-map-archive'
							)
						);
					}
				},
			} );
		}
	);

	$( 'body' ).on(
		'submit',
		'.widget .default-ad-search:not(.directorist_single) .directorist-advanced-filter__form',
		function ( e ) {
			if ( $( '.directorist-instant-search' ).length ) {
				e.preventDefault();
				let _this = $( this );
				let tag = [];
				let price = [];
				let search_by_rating = [];
				let custom_field = {};

				$( this )
					.find( 'input[name^="in_tag["]:checked' )
					.each( function ( index, el ) {
						tag.push( $( el ).val() );
					} );

				$( this )
					.find( 'input[name^="search_by_rating["]:checked' )
					.each( function ( index, el ) {
						search_by_rating.push( $( el ).val() );
					} );

				$( this )
					.find( 'input[name^="price["]' )
					.each( function ( index, el ) {
						price.push( $( el ).val() );
					} );

				$( this )
					.find( '[name^="custom_field"]' )
					.each( function ( index, el ) {
						var name = $( el ).attr( 'name' );
						var type = $( el ).attr( 'type' );
						var post_id = name
							.replace( /(custom_field\[)/, '' )
							.replace( /\]/, '' );
						if ( 'radio' === type ) {
							$.each(
								$(
									"input[name='custom_field[" +
										post_id +
										"]']:checked"
								),
								function () {
									value = $( this ).val();
									custom_field[ post_id ] = value;
								}
							);
						} else if ( 'checkbox' === type ) {
							post_id = post_id.split( '[]' )[ 0 ];
							if ( ! custom_field[ post_id ] ) {
								custom_field[ post_id ] = [];
							}
							$.each(
								$(
									"input[name='custom_field[" +
										post_id +
										"][]']:checked"
								),
								function () {
									var value = $( this ).val();
									custom_field[ post_id ].push( value );
								}
							);
						} else {
							var value = $( el ).val();
							custom_field[ post_id ] = value;
						}
					} );

				let view_href = $(
					'.directorist-viewas .directorist-viewas__item.active'
				).attr( 'href' );
				let view_as =
					view_href && view_href.length
						? view_href.match( /view=.+/ )
						: '';
				let view =
					view_as && view_as.length
						? view_as[ 0 ].replace( /view=/, '' )
						: '';
				let type_href = $(
					'.directorist-type-nav__list .directorist-type-nav__list__current a'
				).attr( 'href' );
				let type =
					type_href && type_href.length
						? type_href.match( /directory_type=.+/ )
						: '';
				let directory_type = getURLParameter(
					type_href,
					'directory_type'
				);
				let data_atts = $( this )
					.closest( '.directorist-instant-search' )
					.attr( 'data-atts' );

				var data = {
					action: 'directorist_instant_search',
					_nonce: directorist.ajax_nonce,
					current_page_id: directorist.current_page_id,
					in_tag: tag,
					price: price,
					search_by_rating: search_by_rating,
					custom_field: custom_field,
					data_atts: JSON.parse( data_atts ),
				};

				var fields = {
					q: $( this ).find( 'input[name="q"]' ).val(),
					in_cat: $( this )
						.find( '.directorist-category-select' )
						.val(),
					in_loc: $( this )
						.find( '.directorist-location-select' )
						.val(),
					price_range: $( this )
						.find( "input[name='price_range']:checked" )
						.val(),
					address: $( this ).find( 'input[name="address"]' ).val(),
					zip: $( this ).find( 'input[name="zip"]' ).val(),
					fax: $( this ).find( 'input[name="fax"]' ).val(),
					email: $( this ).find( 'input[name="email"]' ).val(),
					website: $( this ).find( 'input[name="website"]' ).val(),
					phone: $( this ).find( 'input[name="phone"]' ).val(),
				};

				if ( $( 'input[name="open_now"]' ).is( ':checked' ) ) {
					fields.open_now = $( this )
						.find( 'input[name="open_now"]' )
						.val();
				}

				if ( fields.address && fields.address.length ) {
					fields.cityLat = $( this ).find( '#cityLat' ).val();
					fields.cityLng = $( this ).find( '#cityLng' ).val();
					fields.miles = $( this )
						.find( 'input[name="miles"]' )
						.val();
				}

				if ( fields.zip && fields.zip.length ) {
					fields.zip_cityLat = $( this ).find( '.zip-cityLat' ).val();
					fields.zip_cityLng = $( this ).find( '.zip-cityLng' ).val();
					fields.miles = $( this )
						.find( 'input[name="miles"]' )
						.val();
				}

				if ( fields.address && fields.address.length ) {
					fields.cityLat = $( this ).find( '#cityLat' ).val();
					fields.cityLng = $( this ).find( '#cityLng' ).val();
					fields.miles = $( this )
						.find( 'input[name="miles"]' )
						.val();
				}

				if ( fields.zip && fields.zip.length ) {
					fields.zip_cityLat = $( this ).find( '.zip-cityLat' ).val();
					fields.zip_cityLng = $( this ).find( '.zip-cityLng' ).val();
					fields.miles = $( this )
						.find( '.directorist-custom-range-slider__value input' )
						.val();
				}

				var form_data = {
					...data,
					...fields,
				};

				const allFieldsAreEmpty = Object.values( fields ).every(
					( item ) => ! item
				);
				const tagFieldEmpty = data.in_tag.every( ( item ) => ! item );
				const priceFieldEmpty = data.price.every( ( item ) => ! item );
				const ratingFieldEmpty = data.search_by_rating.every(
					( item ) => ! item
				);
				const customFieldsAreEmpty = Object.values(
					data.custom_field
				).every( ( item ) => ! item );

				if (
					! allFieldsAreEmpty ||
					! tagFieldEmpty ||
					! priceFieldEmpty ||
					! customFieldsAreEmpty ||
					! ratingFieldEmpty
				) {
					if ( view && view.length ) {
						form_data.view = view;
					}

					if ( directory_type && directory_type.length ) {
						form_data.directory_type = directory_type;
					}

					update_instant_search_url( form_data );

					$.ajax( {
						url: directorist.ajaxurl,
						type: 'POST',
						data: form_data,
						beforeSend: function () {
							$( '.directorist-archive-contents' )
								.find( '.directorist-archive-items' )
								.addClass( 'atbdp-form-fade' );
							$( '.directorist-archive-contents' )
								.find(
									'.directorist-header-bar .directorist-advanced-filter'
								)
								.removeClass(
									'directorist-advanced-filter--show'
								);
							$( '.directorist-archive-contents' )
								.find(
									'.directorist-header-bar .directorist-advanced-filter'
								)
								.hide();
							$( document ).scrollTop(
								$( '.directorist-archive-contents' ).offset()
									.top
							);
						},
						success: function ( html ) {
							if ( html.search_result ) {
								$( '.directorist-archive-contents' )
									.find(
										'.directorist-header-found-title span'
									)
									.text( html.count );
								$( '.directorist-archive-contents' )
									.find( '.directorist-archive-items' )
									.replaceWith( html.search_result );
								$( '.directorist-archive-contents' )
									.find( '.directorist-archive-items' )
									.removeClass( 'atbdp-form-fade' );
								$( '.directorist-archive-contents' )
									.find(
										'.directorist-advanced-filter__form .directorist-btn-sm'
									)
									.attr( 'disabled', false );
								window.dispatchEvent(
									new CustomEvent(
										'directorist-instant-search-reloaded'
									)
								);
								window.dispatchEvent(
									new CustomEvent(
										'directorist-reload-listings-map-archive'
									)
								);
							}
						},
					} );
				}
			}
		}
	);

	// Directorist type changes
	$( 'body' ).on(
		'click',
		'.directorist-instant-search .directorist-type-nav__link',
		function ( e ) {
			e.preventDefault();
			// infinite pagination loading reset
			page = 1;
			infinitePaginationIsLoading = false;
			infinitePaginationCompleted = false;

			let _this = $( this );
			let type_href = $( this ).attr( 'href' );
			let type = type_href.match( /directory_type=.+/ );
			//let directory_type = ( type && type.length ) ? type[0].replace( /directory_type=/, '' ) : '';
			let directory_type = getURLParameter( type_href, 'directory_type' );
			let data_atts = $( this )
				.closest( '.directorist-instant-search' )
				.attr( 'data-atts' );
			var form_data = {
				action: 'directorist_instant_search',
				_nonce: directorist.ajax_nonce,
				current_page_id: directorist.current_page_id,
				directory_type: directory_type,
				data_atts: JSON.parse( data_atts ),
			};

			update_instant_search_url( form_data );

			$.ajax( {
				url: directorist.ajaxurl,
				type: 'POST',
				data: form_data,
				beforeSend: function () {
					$( _this )
						.closest( '.directorist-instant-search' )
						.addClass( 'atbdp-form-fade' );
				},
				success: function ( html ) {
					if ( html.directory_type ) {
						$( _this )
							.closest( '.directorist-instant-search' )
							.replaceWith( html.directory_type );
						$( _this )
							.closest( '.directorist-instant-search' )
							.find( '.atbdp-form-fade' )
							.removeClass( 'atbdp-form-fade' );
						window.dispatchEvent(
							new CustomEvent(
								'directorist-instant-search-reloaded'
							)
						);
						window.dispatchEvent(
							new CustomEvent(
								'directorist-reload-listings-map-archive'
							)
						);

						// SearchForm Item in Single Category Location Page Init
						singleCategoryLocationInit();
					}
					let events = [
						new CustomEvent(
							'directorist-instant-search-reloaded'
						),
						new CustomEvent(
							'directorist-search-form-nav-tab-reloaded'
						),
						new CustomEvent( 'directorist-reload-select2-fields' ),
						new CustomEvent( 'directorist-reload-map-api-field' ),
					];

					events.forEach( ( event ) => {
						document.body.dispatchEvent( event );
						window.dispatchEvent( event );
					} );
				},
			} );
		}
	);

	$( 'body' ).on( 'click', '.disabled-link', function ( e ) {
		e.preventDefault();
	} );

	// Directorist view as changes
	$( 'body' ).on(
		'click',
		'.directorist-instant-search .directorist-viewas .directorist-viewas__item',
		function ( e ) {
			e.preventDefault();
			// infinite pagination loading reset
			page = 1;
			infinitePaginationIsLoading = false;
			infinitePaginationCompleted = false;

			let instant_search_element = $( this ).closest(
				'.directorist-instant-search'
			);
			let tag = [];
			let price = [];
			let custom_field = {};

			let sort_href = $( this )
				.closest(
					'.directorist-sortby-dropdown .directorist-dropdown__links__single.active'
				)
				.attr( 'data-link' );
			let sort_by =
				sort_href && sort_href.length
					? sort_href.match( /sort=.+/ )
					: '';
			let sort =
				sort_by && sort_by.length
					? sort_by[ 0 ].replace( /sort=/, '' )
					: '';
			let view_href = $( this ).closest( this ).attr( 'href' );
			let view =
				view_href && view_href.length
					? view_href.match( /view=.+/ )
					: '';
			let type_href = instant_search_element
				.find(
					'.directorist-type-nav__list .directorist-type-nav__list__current a'
				)
				.attr( 'href' );
			let type =
				type_href && type_href.length
					? type_href.match( /directory_type=.+/ )
					: '';
			let directory_type = getURLParameter( type_href, 'directory_type' );
			let page_no = $( this ).closest( '.page-numbers.current' ).text();
			let data_atts = instant_search_element.attr( 'data-atts' );

			// Select Active Form Based on Screen Size
			const advancedForm = instant_search_element.find(
				'.directorist-advanced-filter__form'
			);
			const searchForm = instant_search_element.find(
				'.directorist-search-form'
			);
			const sidebarListing = instant_search_element.find(
				'.listing-with-sidebar'
			);
			const activeForm = sidebarListing.length
				? instant_search_element
				: screen.width > 575
				? advancedForm
				: searchForm;

			// Get Values from Active Form
			activeForm
				.find( 'input[name^="in_tag["]:checked' )
				.each( function ( index, el ) {
					tag.push( $( el ).val() );
				} );

			activeForm
				.find( 'input[name^="price["]' )
				.each( function ( index, el ) {
					price.push( $( el ).val() );
				} );

			activeForm
				.find( '[name^="custom_field"]' )
				.each( function ( index, el ) {
					var name = $( el ).attr( 'name' );
					var type = $( el ).attr( 'type' );
					var post_id = name
						.replace( /(custom_field\[)/, '' )
						.replace( /\]/, '' );

					if ( 'radio' === type ) {
						$.each(
							$(
								"input[name='custom_field[" +
									post_id +
									"]']:checked"
							),
							function () {
								value = $( this ).val();
								custom_field[ post_id ] = value;
							}
						);
					} else if ( 'checkbox' === type ) {
						post_id = post_id.split( '[]' )[ 0 ];
						if ( ! custom_field[ post_id ] ) {
							custom_field[ post_id ] = [];
						}
						$.each(
							$(
								"input[name='custom_field[" +
									post_id +
									"][]']:checked"
							),
							function () {
								var value = $( this ).val();
								custom_field[ post_id ].push( value );
							}
						);
					} else {
						var value = $( el ).val();
						custom_field[ post_id ] = value;
					}
				} );

			let q = activeForm.find( 'input[name="q"]' ).val();
			let in_cat = activeForm
				.find( '.directorist-category-select' )
				.val();
			let in_loc = activeForm
				.find( '.directorist-location-select' )
				.val();
			let price_range = activeForm
				.find( "input[name='price_range']:checked" )
				.val();
			let search_by_rating = activeForm
				.find( 'select[name=search_by_rating]' )
				.val();
			let cityLat = activeForm.find( '#cityLat' ).val();
			let cityLng = activeForm.find( '#cityLng' ).val();
			let miles = activeForm.find( 'input[name="miles"]' ).val();
			let address = activeForm.find( 'input[name="address"]' ).val();
			let zip = activeForm.find( 'input[name="zip"]' ).val();
			let fax = activeForm.find( 'input[name="fax"]' ).val();
			let email = activeForm.find( 'input[name="email"]' ).val();
			let website = activeForm.find( 'input[name="website"]' ).val();
			let phone = activeForm.find( 'input[name="phone"]' ).val();

			// Required fields Check
			let isQueryRequired = activeForm
				.find( 'input[name="q"]' )
				.prop( 'required' );
			let isCategoryRequired = activeForm
				.find( '.directorist-category-select' )
				.prop( 'required' );
			let isLocationRequired = activeForm
				.find( '.directorist-location-select' )
				.prop( 'required' );

			// Validate: If a field is required but empty, return false
			let requiredFieldsAreValid = true;

			if ( isQueryRequired && ! q ) requiredFieldsAreValid = false;
			if ( isCategoryRequired && ( ! in_cat || in_cat.length === 0 ) )
				requiredFieldsAreValid = false;
			if ( isLocationRequired && ( ! in_loc || in_loc.length === 0 ) )
				requiredFieldsAreValid = false;

			$( '.directorist-viewas .directorist-viewas__item' ).removeClass(
				'active'
			);
			$( this ).addClass( 'active' );

			var form_data = {
				action: 'directorist_instant_search',
				_nonce: directorist.ajax_nonce,
				current_page_id: directorist.current_page_id,
				view:
					view && view.length ? view[ 0 ].replace( /view=/, '' ) : '',
				q:
					( requiredFieldsAreValid && q ) ||
					getURLParameter( full_url, 'q' ),
				in_cat:
					( requiredFieldsAreValid && in_cat ) ||
					getURLParameter( full_url, 'in_cat' ),
				in_loc:
					( requiredFieldsAreValid && in_loc ) ||
					getURLParameter( full_url, 'in_loc' ),
				in_tag:
					( requiredFieldsAreValid && tag ) ||
					getURLParameter( full_url, 'in_tag' ),
				price:
					( requiredFieldsAreValid && price ) ||
					getURLParameter( full_url, 'price' ),
				price_range:
					( requiredFieldsAreValid && price_range ) ||
					getURLParameter( full_url, 'price_range' ),
				search_by_rating:
					( requiredFieldsAreValid && search_by_rating ) ||
					getURLParameter( full_url, 'search_by_rating' ),
				cityLat:
					( requiredFieldsAreValid && cityLat ) ||
					getURLParameter( full_url, 'cityLat' ),
				cityLng:
					( requiredFieldsAreValid && cityLng ) ||
					getURLParameter( full_url, 'cityLng' ),
				miles:
					( requiredFieldsAreValid && miles ) ||
					getURLParameter( full_url, 'miles' ),
				address:
					( requiredFieldsAreValid && address ) ||
					getURLParameter( full_url, 'address' ),
				zip:
					( requiredFieldsAreValid && zip ) ||
					getURLParameter( full_url, 'zip' ),
				fax:
					( requiredFieldsAreValid && fax ) ||
					getURLParameter( full_url, 'fax' ),
				email:
					( requiredFieldsAreValid && email ) ||
					getURLParameter( full_url, 'email' ),
				website:
					( requiredFieldsAreValid && website ) ||
					getURLParameter( full_url, 'website' ),
				phone:
					( requiredFieldsAreValid && phone ) ||
					getURLParameter( full_url, 'phone' ),
				custom_field:
					custom_field || getURLParameter( full_url, 'custom_field' ),
				data_atts: JSON.parse( data_atts ),
			};

			//business hours
			if ( $( 'input[name="open_now"]' ).is( ':checked' ) ) {
				form_data.open_now = activeForm
					.find( 'input[name="open_now"]' )
					.val();
			}

			if ( form_data.address && form_data.address.length ) {
				form_data.cityLat = activeForm.find( '#cityLat' ).val();
				form_data.cityLng = activeForm.find( '#cityLng' ).val();
				form_data.miles = activeForm
					.find( 'input[name="miles"]' )
					.val();
			}

			if ( form_data.zip && form_data.zip.length ) {
				form_data.zip_cityLat = activeForm.find( '.zip-cityLat' ).val();
				form_data.zip_cityLng = activeForm.find( '.zip-cityLng' ).val();
				form_data.miles = activeForm
					.find( 'input[name="miles"]' )
					.val();
			}

			if ( page_no && page_no.length ) {
				form_data.paged = page_no;
			}

			if ( directory_type && directory_type.length ) {
				form_data.directory_type = directory_type;
			}

			if ( sort && sort.length ) {
				form_data.sort = sort;
			}

			$.ajax( {
				url: directorist.ajaxurl,
				type: 'POST',
				data: form_data,
				beforeSend: function () {
					instant_search_element
						.find( '.directorist-archive-items' )
						.addClass( 'atbdp-form-fade' );
					instant_search_element
						.find(
							'.directorist-viewas-dropdown .directorist-dropdown__links__single'
						)
						.addClass( 'disabled-link' );
					instant_search_element
						.find( '.directorist-dropdown__links-js a' )
						.removeClass( 'directorist-dropdown__links__single' );
					instant_search_element
						.find( '.directorist-archive-items' )
						.addClass( 'atbdp-form-fade' );
					instant_search_element
						.find( '.directorist-dropdown__links' )
						.hide();
					instant_search_element
						.find(
							'.directorist-header-bar .directorist-advanced-filter'
						)
						.removeClass( 'directorist-advanced-filter--show' );
					instant_search_element
						.find(
							'.directorist-header-bar .directorist-advanced-filter'
						)
						.css( 'visibility', 'hidden' );
					//$(document).scrollTop( $(this).closest(".directorist-instant-search").offset().top );
				},
				success: function ( html ) {
					if ( html.view_as ) {
						instant_search_element
							.find( '.directorist-header-found-title span' )
							.text( html.count );
						instant_search_element
							.find( '.directorist-archive-items' )
							.replaceWith( html.view_as );
						instant_search_element
							.find( '.directorist-archive-items' )
							.removeClass( 'atbdp-form-fade' );
						instant_search_element
							.find(
								'.directorist-viewas-dropdown .directorist-dropdown__links__single'
							)
							.removeClass( 'disabled-link' );
						instant_search_element
							.find( '.directorist-dropdown__links-js a' )
							.addClass( 'directorist-dropdown__links__single' );

						window.dispatchEvent(
							new CustomEvent(
								'directorist-instant-search-reloaded'
							)
						);
						window.dispatchEvent(
							new CustomEvent(
								'directorist-reload-listings-map-archive'
							)
						);
						instant_search_element
							.find(
								'.directorist-header-bar .directorist-advanced-filter'
							)
							.css( 'visibility', 'visible' );
					}
				},
			} );
		}
	);

	$(
		'.directorist-instant-search .directorist-dropdown__links__single-js'
	).off( 'click' );

	// Directorist sort by changes
	$( 'body' ).on(
		'click',
		'.directorist-instant-search .directorist-sortby-dropdown .directorist-dropdown__links__single-js',
		function ( e ) {
			e.preventDefault();
			// infinite pagination loading reset
			page = 1;
			infinitePaginationIsLoading = false;
			infinitePaginationCompleted = false;

			let instant_search_element = $( this ).closest(
				'.directorist-instant-search'
			);
			let tag = [];
			let price = [];
			let custom_field = {};

			let view_href = instant_search_element
				.find( '.directorist-viewas .directorist-viewas__item.active' )
				.attr( 'href' );
			let view_as =
				view_href && view_href.length
					? view_href.match( /view=.+/ )
					: '';
			let view =
				view_as && view_as.length
					? view_as[ 0 ].replace( /view=/, '' )
					: '';
			let sort_href = $( this ).closest( this ).attr( 'data-link' );
			let sort_by = sort_href.match( /sort=.+/ );
			let type_href = instant_search_element
				.find(
					'.directorist-type-nav__list .directorist-type-nav__list__current a'
				)
				.attr( 'href' );
			let type =
				type_href && type_href.length
					? type_href.match( /directory_type=.+/ )
					: '';
			let directory_type = getURLParameter( type_href, 'directory_type' );
			let data_atts = instant_search_element.attr( 'data-atts' );

			instant_search_element
				.find(
					'.directorist-sortby-dropdown .directorist-dropdown__links__single'
				)
				.removeClass( 'active' );
			$( this ).addClass( 'active' );

			// Select Active Form Based on Screen Size
			const advancedForm = instant_search_element.find(
				'.directorist-advanced-filter__form'
			);
			const searchForm = instant_search_element.find(
				'.directorist-search-form'
			);
			const sidebarListing = instant_search_element.find(
				'.listing-with-sidebar'
			);
			const activeForm = sidebarListing.length
				? instant_search_element
				: screen.width > 575
				? advancedForm
				: searchForm;

			// Get Values from Active Form
			activeForm
				.find( 'input[name^="in_tag["]:checked' )
				.each( function ( index, el ) {
					tag.push( $( el ).val() );
				} );

			activeForm
				.find( 'input[name^="price["]' )
				.each( function ( index, el ) {
					price.push( $( el ).val() );
				} );

			activeForm
				.find( '[name^="custom_field"]' )
				.each( function ( index, el ) {
					var name = $( el ).attr( 'name' );
					var type = $( el ).attr( 'type' );
					var post_id = name
						.replace( /(custom_field\[)/, '' )
						.replace( /\]/, '' );
					if ( 'radio' === type ) {
						$.each(
							$(
								"input[name='custom_field[" +
									post_id +
									"]']:checked"
							),
							function () {
								value = $( this ).val();
								custom_field[ post_id ] = value;
							}
						);
					} else if ( 'checkbox' === type ) {
						post_id = post_id.split( '[]' )[ 0 ];
						if ( ! custom_field[ post_id ] ) {
							custom_field[ post_id ] = [];
						}
						$.each(
							$(
								"input[name='custom_field[" +
									post_id +
									"][]']:checked"
							),
							function () {
								var value = $( this ).val();
								custom_field[ post_id ].push( value );
							}
						);
					} else {
						var value = $( el ).val();
						custom_field[ post_id ] = value;
					}
				} );

			let q = activeForm.find( 'input[name="q"]' ).val();
			let in_cat = activeForm
				.find( '.directorist-category-select' )
				.val();
			let in_loc = activeForm
				.find( '.directorist-location-select' )
				.val();
			let price_range = activeForm
				.find( "input[name='price_range']:checked" )
				.val();
			let search_by_rating = activeForm
				.find( 'select[name=search_by_rating]' )
				.val();
			let cityLat = activeForm.find( '#cityLat' ).val();
			let cityLng = activeForm.find( '#cityLng' ).val();
			let miles = activeForm.find( 'input[name="miles"]' ).val();
			let address = activeForm.find( 'input[name="address"]' ).val();
			let zip = activeForm.find( 'input[name="zip"]' ).val();
			let fax = activeForm.find( 'input[name="fax"]' ).val();
			let email = activeForm.find( 'input[name="email"]' ).val();
			let website = activeForm.find( 'input[name="website"]' ).val();
			let phone = activeForm.find( 'input[name="phone"]' ).val();

			var form_data = {
				action: 'directorist_instant_search',
				_nonce: directorist.ajax_nonce,
				current_page_id: directorist.current_page_id,
				sort:
					sort_by && sort_by.length
						? sort_by[ 0 ].replace( /sort=/, '' )
						: '',
				q: q || getURLParameter( full_url, 'q' ),
				in_cat: in_cat || getURLParameter( full_url, 'in_cat' ),
				in_loc: in_loc || getURLParameter( full_url, 'in_loc' ),
				in_tag: tag || getURLParameter( full_url, 'in_tag' ),
				price: price || getURLParameter( full_url, 'price' ),
				price_range:
					price_range || getURLParameter( full_url, 'price_range' ),
				search_by_rating:
					search_by_rating ||
					getURLParameter( full_url, 'search_by_rating' ),
				cityLat: cityLat || getURLParameter( full_url, 'cityLat' ),
				cityLng: cityLng || getURLParameter( full_url, 'cityLng' ),
				miles: miles || getURLParameter( full_url, 'miles' ),
				address: address || getURLParameter( full_url, 'address' ),
				zip: zip || getURLParameter( full_url, 'zip' ),
				fax: fax || getURLParameter( full_url, 'fax' ),
				email: email || getURLParameter( full_url, 'email' ),
				website: website || getURLParameter( full_url, 'website' ),
				phone: phone || getURLParameter( full_url, 'phone' ),
				custom_field:
					custom_field || getURLParameter( full_url, 'custom_field' ),
				view: view,
				data_atts: JSON.parse( data_atts ),
			};

			//business hours
			if ( $( 'input[name="open_now"]' ).is( ':checked' ) ) {
				form_data.open_now = activeForm
					.find( 'input[name="open_now"]' )
					.val();
			}

			if ( form_data.address && form_data.address.length ) {
				form_data.cityLat = activeForm.find( '#cityLat' ).val();
				form_data.cityLng = activeForm.find( '#cityLng' ).val();
				form_data.miles = activeForm
					.find( 'input[name="miles"]' )
					.val();
			}

			if ( form_data.zip && form_data.zip.length ) {
				form_data.zip_cityLat = activeForm.find( '.zip-cityLat' ).val();
				form_data.zip_cityLng = activeForm.find( '.zip-cityLng' ).val();
				form_data.miles = activeForm
					.find( 'input[name="miles"]' )
					.val();
			}

			if ( directory_type && directory_type.length ) {
				form_data.directory_type = directory_type;
			}

			$.ajax( {
				url: directorist.ajaxurl,
				type: 'POST',
				data: form_data,
				beforeSend: function () {
					instant_search_element
						.find(
							'.directorist-sortby-dropdown .directorist-dropdown__links__single-js'
						)
						.addClass( 'disabled-link' );
					instant_search_element
						.find( '.directorist-dropdown__links-js a' )
						.removeClass(
							'directorist-dropdown__links__single-js'
						);
					instant_search_element
						.find( '.directorist-archive-items' )
						.addClass( 'atbdp-form-fade' );
					instant_search_element
						.find( '.directorist-dropdown__links' )
						.hide();
					const advance_filter = instant_search_element.find(
						'.directorist-header-bar .directorist-advanced-filter'
					)[ 0 ];
					$( advance_filter ).removeClass(
						'directorist-advanced-filter--show'
					);
					$( advance_filter ).hide();
					$( document ).scrollTop(
						instant_search_element.offset().top
					);
				},
				success: function ( html ) {
					if ( html.view_as ) {
						instant_search_element
							.find( '.directorist-header-found-title span' )
							.text( html.count );
						instant_search_element
							.find( '.directorist-archive-items' )
							.replaceWith( html.view_as );
						instant_search_element
							.find( '.directorist-archive-items' )
							.removeClass( 'atbdp-form-fade' );
						instant_search_element
							.find(
								'.directorist-sortby-dropdown .directorist-dropdown__links__single-js'
							)
							.removeClass( 'disabled-link' );
						instant_search_element
							.find( '.directorist-dropdown__links-js a' )
							.addClass(
								'directorist-dropdown__links__single-js'
							);
					}
					window.dispatchEvent(
						new CustomEvent( 'directorist-instant-search-reloaded' )
					);
					window.dispatchEvent(
						new CustomEvent(
							'directorist-reload-listings-map-archive'
						)
					);
				},
			} );
		}
	);

	// Directorist pagination
	$( 'body' ).on(
		'click',
		'.directorist-instant-search .directorist-pagination .page-numbers',
		function ( e ) {
			e.preventDefault();
			let tag = [];
			let price = [];
			let custom_field = {};
			const $container = $( this ).closest(
				'.directorist-instant-search'
			);
			const $directory_nav = $container.find(
				'.directorist-type-nav__list'
			);

			let sort_href = $container
				.find(
					'.directorist-sortby-dropdown .directorist-dropdown__links__single.active'
				)
				.attr( 'data-link' );
			let sort_by =
				sort_href && sort_href.length
					? sort_href.match( /sort=.+/ )
					: '';
			let sort =
				sort_by && sort_by.length
					? sort_by[ 0 ].replace( /sort=/, '' )
					: '';
			let view_href = $container
				.find( '.directorist-viewas .directorist-viewas__item.active' )
				.attr( 'href' );
			let view_as =
				view_href && view_href.length
					? view_href.match( /view=.+/ )
					: '';
			let view =
				view_as && view_as.length
					? view_as[ 0 ].replace( /view=/, '' )
					: '';
			let type_href = $directory_nav
				.find( '.directorist-type-nav__list__current a' )
				.attr( 'href' );
			let type =
				type_href && type_href.length
					? type_href.match( /directory_type=.+/ )
					: '';
			let directory_type = getURLParameter( type_href, 'directory_type' );
			let data_atts = $container.attr( 'data-atts' );

			// Select Active Form Based on Screen Size
			const advancedForm = $container.find(
				'.directorist-advanced-filter__form'
			);
			const searchForm = $container.find( '.directorist-search-form' );
			const sidebarListing = $container.find( '.listing-with-sidebar' );
			const activeForm = sidebarListing.length
				? $container
				: screen.width > 575
				? advancedForm
				: searchForm;

			// Get Values from Active Form
			activeForm
				.find( 'input[name^="in_tag["]:checked' )
				.each( function ( index, el ) {
					tag.push( $( el ).val() );
				} );

			activeForm
				.find( 'input[name^="price["]' )
				.each( function ( index, el ) {
					price.push( $( el ).val() );
				} );

			activeForm
				.find( '[name^="custom_field"]' )
				.each( function ( index, el ) {
					var name = $( el ).attr( 'name' );
					var type = $( el ).attr( 'type' );
					var post_id = name
						.replace( /(custom_field\[)/, '' )
						.replace( /\]/, '' );
					if ( 'radio' === type ) {
						$.each(
							$(
								"input[name='custom_field[" +
									post_id +
									"]']:checked"
							),
							function () {
								value = $( this ).val();
								custom_field[ post_id ] = value;
							}
						);
					} else if ( 'checkbox' === type ) {
						post_id = post_id.split( '[]' )[ 0 ];
						if ( ! custom_field[ post_id ] ) {
							custom_field[ post_id ] = [];
						}
						$.each(
							$(
								"input[name='custom_field[" +
									post_id +
									"][]']:checked"
							),
							function () {
								var value = $( this ).val();
								custom_field[ post_id ].push( value );
							}
						);
					} else {
						var value = $( el ).val();
						custom_field[ post_id ] = value;
					}
				} );

			let q = activeForm.find( 'input[name="q"]' ).val();
			let in_cat = activeForm
				.find( '.directorist-category-select' )
				.val();
			let in_loc = activeForm
				.find( '.directorist-location-select' )
				.val();
			let price_range = activeForm
				.find( "input[name='price_range']:checked" )
				.val();
			let search_by_rating = activeForm
				.find( 'select[name=search_by_rating]' )
				.val();
			let cityLat = activeForm.find( '#cityLat' ).val();
			let cityLng = activeForm.find( '#cityLng' ).val();
			let address = activeForm.find( 'input[name="address"]' ).val();
			let zip = activeForm.find( 'input[name="zip"]' ).val();
			let miles =
				( address || zip ) &&
				activeForm.find( 'input[name="miles"]' ).val();
			let fax = activeForm.find( 'input[name="fax"]' ).val();
			let email = activeForm.find( 'input[name="email"]' ).val();
			let website = activeForm.find( 'input[name="website"]' ).val();
			let phone = activeForm.find( 'input[name="phone"]' ).val();

			$container
				.find( '.directorist-pagination .page-numbers' )
				.removeClass( 'current' );
			$( this ).addClass( 'current' );

			var paginate_link = $( this ).attr( 'href' );
			var page_no = '';

			if ( paginate_link ) {
				var pageMatch = paginate_link.match( /(?:page\/|paged=)(\d+)/ );
				if ( pageMatch ) {
					page_no = pageMatch[ 1 ]; // Extracts only the numeric value
				}
			}
			console.log( page_no );
			var form_data = {
				action: 'directorist_instant_search',
				_nonce: directorist.ajax_nonce,
				current_page_id: directorist.current_page_id,
				q: q,
				in_cat: in_cat,
				in_loc: in_loc,
				in_tag: tag,
				price: price,
				price_range: price_range,
				search_by_rating: search_by_rating,
				cityLat: cityLat,
				cityLng: cityLng,
				address: address,
				zip: zip,
				fax: fax,
				email: email,
				website: website,
				phone: phone,
				custom_field: custom_field,
				miles: miles,
				view: view,
				paged: page_no,
				data_atts: JSON.parse( data_atts ),
			};

			//business hours
			if ( $( 'input[name="open_now"]' ).is( ':checked' ) ) {
				form_data.open_now = activeForm
					.find( 'input[name="open_now"]' )
					.val();
			}

			if ( form_data.address && form_data.address.length ) {
				form_data.cityLat = activeForm.find( '#cityLat' ).val();
				form_data.cityLng = activeForm.find( '#cityLng' ).val();
				form_data.miles = activeForm
					.find( 'input[name="miles"]' )
					.val();
			}

			if ( form_data.zip && form_data.zip.length ) {
				form_data.zip_cityLat = activeForm.find( '.zip-cityLat' ).val();
				form_data.zip_cityLng = activeForm.find( '.zip-cityLng' ).val();
				form_data.miles = activeForm
					.find( 'input[name="miles"]' )
					.val();
			}

			if ( directory_type && directory_type.length ) {
				form_data.directory_type = directory_type;
			}

			if ( sort && sort.length ) {
				form_data.sort = sort;
			}

			if ( $directory_nav.is( ':hidden' ) ) {
				form_data.directory_nav = false;
			}

			update_instant_search_url( form_data );

			$.ajax( {
				url: directorist.ajaxurl,
				type: 'POST',
				data: form_data,
				beforeSend: function () {
					$container
						.find( '.directorist-archive-items' )
						.addClass( 'atbdp-form-fade' );
				},
				success: function ( html ) {
					if ( html.view_as ) {
						$container
							.find( '.directorist-header-found-title span' )
							.text( html.count );
						$container
							.find( '.directorist-archive-items' )
							.replaceWith( html.view_as );
						$container
							.find( '.directorist-archive-items' )
							.removeClass( 'atbdp-form-fade' );
						$( document ).scrollTop( $container.offset().top );
					}
					window.dispatchEvent(
						new CustomEvent( 'directorist-instant-search-reloaded' )
					);
					window.dispatchEvent(
						new CustomEvent(
							'directorist-reload-listings-map-archive'
						)
					);
				},
			} );
		}
	);

	// Helper function to determine the active form
	function getActiveForm( instantSearchElement ) {
=======
	// Determine the active form
	function getActiveForm(instantSearchElement) {
>>>>>>> development
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

<<<<<<< HEAD
	// Helper function to build form data
	function buildFormData( activeForm, instantSearchElement ) {
		const tag = [];
		const price = [];
		const customField = {};
		const dataAtts = JSON.parse( instantSearchElement.attr( 'data-atts' ) );

		activeForm
			.find( 'input[name^="in_tag["]:checked' )
			.each( ( _, el ) => tag.push( $( el ).val() ) );
		activeForm
			.find( 'input[name^="price["]' )
			.each( ( _, el ) => price.push( $( el ).val() ) );

		activeForm.find( '[name^="custom_field"]' ).each( ( _, el ) => {
			const name = $( el ).attr( 'name' );
			const type = $( el ).attr( 'type' );
			const postId = name
				.replace( /(custom_field\[)/, '' )
				.replace( /\]/, '' )
				.split( '[]' )[ 0 ];

			if ( type === 'radio' ) {
				customField[ postId ] = activeForm
					.find( `input[name='custom_field[${ postId }]']:checked` )
					.val();
			} else if ( type === 'checkbox' ) {
				customField[ postId ] = activeForm
					.find( `input[name='custom_field[${ postId }][]']:checked` )
					.map( ( _, e ) => $( e ).val() )
					.get();
			} else {
				customField[ postId ] = $( el ).val();
			}
		} );

		let view_href = $(
			'.directorist-viewas .directorist-viewas__item.active'
		).attr( 'href' );
		let view_as =
			view_href && view_href.length ? view_href.match( /view=.+/ ) : '';
		let view =
			view_as && view_as.length
				? view_as[ 0 ].replace( /view=/, '' )
				: '';

		const getValue = ( selector, fallback ) =>
			activeForm.find( selector ).val() || fallback;
		return {
			action: 'directorist_instant_search',
			_nonce: directorist.ajax_nonce,
			current_page_id: directorist.current_page_id,
			q: getValue( 'input[name="q"]', getURLParameter( full_url, 'q' ) ),
			in_cat: getValue(
				'.directorist-category-select',
				getURLParameter( full_url, 'in_cat' )
			),
			in_loc: getValue(
				'.directorist-location-select',
				getURLParameter( full_url, 'in_loc' )
			),
			in_tag: tag || getURLParameter( full_url, 'in_tag' ),
			price: price || getURLParameter( full_url, 'price' ),
			price_range: getValue(
				"input[name='price_range']:checked",
				getURLParameter( full_url, 'price_range' )
			),
			search_by_rating: getValue(
				'select[name=search_by_rating]',
				getURLParameter( full_url, 'search_by_rating' )
			),
			cityLat: getValue(
				'#cityLat',
				getURLParameter( full_url, 'cityLat' )
			),
			cityLng: getValue(
				'#cityLng',
				getURLParameter( full_url, 'cityLng' )
			),
			miles: getValue(
				'input[name="miles"]',
				getURLParameter( full_url, 'miles' )
			),
			address: getValue(
				'input[name="address"]',
				getURLParameter( full_url, 'address' )
			),
			zip: getValue(
				'input[name="zip"]',
				getURLParameter( full_url, 'zip' )
			),
			fax: getValue(
				'input[name="fax"]',
				getURLParameter( full_url, 'fax' )
			),
			email: getValue(
				'input[name="email"]',
				getURLParameter( full_url, 'email' )
			),
			website: getValue(
				'input[name="website"]',
				getURLParameter( full_url, 'website' )
			),
			phone: getValue(
				'input[name="phone"]',
				getURLParameter( full_url, 'phone' )
			),
			custom_field: customField,
			view: view,
			paged: page,
			data_atts: dataAtts,
			sort: getSortValue( instantSearchElement ),
			directory_type: getDirectoryType( instantSearchElement ),
			open_now: activeForm.find( 'input[name="open_now"]:checked' ).val(),
		};
	}

	// Helper function to get sort value
	function getSortValue( instantSearchElement ) {
		const sortHref = instantSearchElement
			.find(
				'.directorist-sortby-dropdown .directorist-dropdown__links__single.active'
			)
			.data( 'link' );
		return sortHref ? sortHref.split( 'sort=' )[ 1 ] : '';
	}

	// Helper function to get directory type
	function getDirectoryType( instantSearchElement ) {
		const typeHref = instantSearchElement
			.find(
				'.directorist-type-nav__list .directorist-type-nav__list__current a'
			)
			.attr( 'href' );
		return typeHref ? getURLParameter( typeHref, 'directory_type' ) : '';
	}

	// AJAX call to load more listings
	function loadMoreListings( formData ) {
		let loadingDiv;
		const container = $(
			'.directorist-infinite-scroll .directorist-container-fluid .directorist-row'
		);

		$.ajax( {
			url: directorist.ajaxurl,
			type: 'POST',
			data: formData,
			beforeSend: function () {
				loadingDiv = $( '<div>', {
					class: 'directorist-on-scroll-loading',
				} ).append(
					$( '<div>', { class: 'directorist-spinner' } ),
					$( '<span>' ).text( 'Loading more...' )
				);
				container.append( loadingDiv );
			},
			success: function ( html ) {
				if ( loadingDiv ) loadingDiv.remove();

				if ( html.count > 0 ) {
					container.append( html.render_listings );
				} else {
					infinitePaginationCompleted = true;
				}

				triggerCustomEvents();
			},
			complete: function () {
				infinitePaginationIsLoading = false;
				if ( loadingDiv ) loadingDiv.remove();
			},
		} );
	}

	// Helper function to trigger custom events
=======
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
>>>>>>> development
	function triggerCustomEvents() {
		window.dispatchEvent(
			new Event( 'directorist-instant-search-reloaded' )
		);
		window.dispatchEvent(
			new Event( 'directorist-reload-listings-map-archive' )
		);
	}

<<<<<<< HEAD
	// Filter on AJAX Search
	function filterListing( searchElm ) {
		if ( ! searchElm ) {
			return;
		}

		// infinite pagination loading reset
		page = 1;
		infinitePaginationIsLoading = false;
		infinitePaginationCompleted = false;

		let _this = searchElm;
		let tag = [];
		let price = [];
		let search_by_rating = [];
		let custom_field = {};

		searchElm
			.find( 'input[name^="in_tag[]"]:checked' )
			.each( function ( index, el ) {
				tag.push( $( el ).val() );
			} );

		searchElm
			.find( 'input[name^="search_by_rating[]"]:checked' )
			.each( function ( index, el ) {
				search_by_rating.push( $( el ).val() );
			} );

		searchElm.find( 'input[name^="price["]' ).each( function ( index, el ) {
			price.push( $( el ).val() );
		} );

		searchElm
			.find( '[name^="custom_field"]' )
			.each( function ( index, el ) {
				var name = $( el ).attr( 'name' );
				var type = $( el ).attr( 'type' );
				var post_id = name
					.replace( /(custom_field\[)/, '' )
					.replace( /\]/, '' );
				if ( 'radio' === type ) {
					$.each(
						$(
							"input[name='custom_field[" +
								post_id +
								"]']:checked"
						),
						function () {
							value = $( this ).val();
							custom_field[ post_id ] = value;
						}
					);
				} else if ( 'checkbox' === type ) {
					post_id = post_id.split( '[]' )[ 0 ];
					if ( ! custom_field[ post_id ] ) {
						custom_field[ post_id ] = [];
					}
					$.each(
						$(
							"input[name='custom_field[" +
								post_id +
								"][]']:checked"
						),
						function () {
							var value = $( this ).val();
							custom_field[ post_id ].push( value );
						}
					);
				} else {
					var value = $( el ).val();
					custom_field[ post_id ] = value;
				}
			} );

		let view_href = $(
			'.directorist-viewas .directorist-viewas__item.active'
		).attr( 'href' );
		let view_as =
			view_href && view_href.length ? view_href.match( /view=.+/ ) : '';
		let view =
			view_as && view_as.length
				? view_as[ 0 ].replace( /view=/, '' )
				: '';
		let type_href = $(
			'.directorist-type-nav__list .directorist-type-nav__list__current a'
		).attr( 'href' );
		let type =
			type_href && type_href.length
				? type_href.match( /directory_type=.+/ )
				: '';
		let directory_type = getURLParameter( type_href, 'directory_type' );
		let data_atts = $( '.directorist-instant-search' ).attr( 'data-atts' );

		var data = {
			action: 'directorist_instant_search',
			_nonce: directorist.ajax_nonce,
			current_page_id: directorist.current_page_id,
			in_tag: tag,
			price: price,
			search_by_rating: search_by_rating,
			custom_field: custom_field,
			data_atts: JSON.parse( data_atts ),
		};

		var fields = {
			q: searchElm.find( 'input[name="q"]' ).val(),
			in_cat: searchElm.find( '.directorist-category-select' ).val(),
			in_loc: searchElm.find( '.directorist-location-select' ).val(),
			price_range: searchElm
				.find( "input[name='price_range']:checked" )
				.val(),
			address: searchElm.find( 'input[name="address"]' ).val(),
			zip: searchElm.find( 'input[name="zip"]' ).val(),
			fax: searchElm.find( 'input[name="fax"]' ).val(),
			email: searchElm.find( 'input[name="email"]' ).val(),
			website: searchElm.find( 'input[name="website"]' ).val(),
			phone: searchElm.find( 'input[name="phone"]' ).val(),
		};

		//business hours
		if ( $( 'input[name="open_now"]' ).is( ':checked' ) ) {
			fields.open_now = searchElm.find( 'input[name="open_now"]' ).val();
		}

		if ( fields.address && fields.address.length ) {
			fields.cityLat = searchElm.find( '#cityLat' ).val();
			fields.cityLng = searchElm.find( '#cityLng' ).val();
			fields.miles = searchElm.find( 'input[name="miles"]' ).val();
		}

		if ( fields.zip && fields.zip.length ) {
			fields.zip_cityLat = searchElm.find( '.zip-cityLat' ).val();
			fields.zip_cityLng = searchElm.find( '.zip-cityLng' ).val();
			fields.miles = searchElm.find( 'input[name="miles"]' ).val();
		}

		var form_data = {
			...data,
			...fields,
		};

		if ( view && view.length ) {
			form_data.view = view;
		}

		if ( directory_type && directory_type.length ) {
			form_data.directory_type = directory_type;
		}

		update_instant_search_url( form_data );

		$.ajax( {
			url: directorist.ajaxurl,
			type: 'POST',
			data: form_data,
			beforeSend: function () {
				$( _this )
					.closest( '.directorist-instant-search' )
					.find(
						'.directorist-advanced-filter__form .directorist-btn-sm'
					)
					.attr( 'disabled', true );
				$( _this )
					.closest( '.directorist-instant-search' )
					.find( '.directorist-archive-items' )
					.addClass( 'atbdp-form-fade' );
				$( _this )
					.closest( '.directorist-instant-search' )
					.find(
						'.directorist-header-bar .directorist-advanced-filter'
					)
					.removeClass( 'directorist-advanced-filter--show' );
				$( _this )
					.closest( '.directorist-instant-search' )
					.find(
						'.directorist-header-bar .directorist-advanced-filter'
					)
					.hide();
				if ( $( '.directorist-instant-search' ).offset() > 0 ) {
					$( document ).scrollTop(
						$( _this )
							.closest( '.directorist-instant-search' )
							.offset().top
					);
				}
			},
			success: function ( html ) {
				if ( html.search_result ) {
					$( _this )
						.closest( '.directorist-instant-search' )
						.find( '.directorist-header-found-title' )
						.remove();
					$( _this )
						.closest( '.directorist-instant-search' )
						.find( '.dsa-save-search-container' )
						.remove();
					if ( String( html.header_title ) ) {
						$( _this )
							.closest( '.directorist-instant-search' )
							.find( '.directorist-listings-header__left' )
							.append( html.header_title );
						$( _this )
							.closest( '.directorist-instant-search' )
							.find( '.directorist-header-found-title span' )
							.text( html.count );
					}
					$( _this )
						.closest( '.directorist-instant-search' )
						.find( '.directorist-archive-items' )
						.replaceWith( html.search_result );
					$( _this )
						.closest( '.directorist-instant-search' )
						.find( '.directorist-archive-items' )
						.removeClass( 'atbdp-form-fade' );
					$( _this )
						.closest( '.directorist-instant-search' )
						.find(
							'.directorist-advanced-filter__form .directorist-btn-sm'
						)
						.attr( 'disabled', false );
					window.dispatchEvent(
						new CustomEvent( 'directorist-instant-search-reloaded' )
					);
					window.dispatchEvent(
						new CustomEvent(
							'directorist-reload-listings-map-archive'
						)
					);

					var website_name = directorist.site_name; // This is dynamically set from WordPress

					// Construct the new meta title
					var new_meta_title = ''; // Start with an empty title
					// Check if the category is selected and append to the title
					if ( String( html.category_name ) ) {
						new_meta_title += html.category_name;
					}

					// Check if location is selected and append with proper formatting
					if ( String( html.location_name ) ) {
						if ( String( html.category_name ) ) {
							new_meta_title += ' within ' + html.location_name; // If category exists, add with a comma
						} else {
							new_meta_title += html.location_name; // If no category, just add location
						}
					}

					// Check if address is selected and append with proper formatting
					if ( fields.address ) {
						if ( fields.in_cat || fields.in_loc ) {
							new_meta_title += ' near ' + fields.address; // If category or location exists, add "near"
						} else {
							new_meta_title += fields.address; // Default to just the address
						}
					}

					// Append website name to the meta title with a pipe separator
					if ( new_meta_title ) {
						new_meta_title += ' | ' + website_name; // Append the website name only if the title has content
					} else {
						new_meta_title = website_name; // Default to only the website name if no other title parts are present
					}

					// Update the meta title dynamically
					document.title = new_meta_title;
				}
			},
		} );
	}

=======
>>>>>>> development
	// Range Slider searching observer
	function initObserver() {
		let targetNodes = document.querySelectorAll(
			'.directorist-instant-search .directorist-custom-range-slider__value input'
		);

		targetNodes.forEach( ( targetNode ) => {
			let searchElm = $( targetNode.closest( 'form' ) );

			if ( targetNode ) {
				let timeout;
<<<<<<< HEAD
				const observerCallback = ( mutationList, observer ) => {
					for ( const mutation of mutationList ) {
						if ( mutation.attributeName == 'value' ) {
							clearTimeout( timeout );
							timeout = setTimeout( () => {
								filterListing( searchElm );
							}, 250 );
=======
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
>>>>>>> development
						}
					}
				};

				const observer = new MutationObserver( observerCallback );
				observer.observe( targetNode, {
					attributes: true,
					childList: true,
					subtree: true,
				} );
			}
		} );
	}

	// Single Location Category Page Search Form Item Disable
	function singleCategoryLocationInit() {
		const directoristArchiveContents = document.querySelector(
			'.directorist-archive-contents'
		);
		if ( ! directoristArchiveContents ) {
			return;
		}

		const directoristDataAttributes = directoristArchiveContents.getAttribute(
			'data-atts'
		);
		const { shortcode, location, category } = JSON.parse(
			directoristDataAttributes
		);

		if ( shortcode === 'directorist_category' && category.trim() !== '' ) {
			const categorySelect = document.querySelector(
				'.directorist-search-form .directorist-category-select'
			);
			if ( categorySelect ) {
				categorySelect
					.closest( '.directorist-search-category' )
					.classList.add(
						'directorist-search-form__single-category'
					);
			}
		}

		if ( shortcode === 'directorist_location' && location.trim() !== '' ) {
			const locationSelect = document.querySelector(
				'.directorist-search-form .directorist-location-select'
			);
			if ( locationSelect ) {
				locationSelect
					.closest( '.directorist-search-location' )
					.classList.add(
						'directorist-search-form__single-location'
					);
			}
		}
	}

	/** 
		Event Listeners 
	*/

	// sidebar on keyup searching
	$( 'body' ).on(
		'keyup',
		'.directorist-instant-search .listing-with-sidebar form',
		debounce( function ( e ) {
			if (
				$( e.target ).closest(
					'.directorist-custom-range-slider__value'
				).length > 0
			) {
				return; // Skip search for this element
			}

			e.preventDefault();
<<<<<<< HEAD
			var searchElm = $( this ).closest( '.listing-with-sidebar' );
			filterListing( searchElm );
		}, 250 )
	);

	// sidebar on change searching
	$( 'body' ).on(
		'change',
		".directorist-instant-search .listing-with-sidebar input[type='checkbox'],.directorist-instant-search .listing-with-sidebar input[type='radio'], .directorist-custom-range-slider__wrap .directorist-custom-range-slider__range, .directorist-search-location .location-name",
		debounce( function ( e ) {
			e.preventDefault();
			var searchElm = $( this ).closest( '.listing-with-sidebar' );
			filterListing( searchElm );
		}, 250 )
	);

	// sidebar on change location, zipcode changing
	$( 'body' ).on(
=======
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
>>>>>>> development
		'change',
		'.directorist-instant-search .listing-with-sidebar .directorist-search-location, .directorist-instant-search .listing-with-sidebar .directorist-zipcode-search',
		debounce( function ( e ) {
			e.preventDefault();
<<<<<<< HEAD

			const searchElm = $( this ).closest( '.listing-with-sidebar' );
=======
			const searchElm = $(this).closest('.listing-with-sidebar');
>>>>>>> development

			// If it's a location field, ensure it has a value before triggering the filter
			if ( $( this ).hasClass( 'directorist-search-location' ) ) {
				const locationField = $( this ).find( 'input[name="address"]' );
				if ( ! locationField.val() ) {
					return;
				}
			}

<<<<<<< HEAD
			filterListing( searchElm );
		}, 250 )
	);

	// select on change with value - searching
	$( 'body' ).on(
=======
			// Instant search with required value
			performInstantSearchWithRequiredValue(searchElm);
		}, 250)
	);

	// sidebar on change searching - select
	$('body').on(
>>>>>>> development
		'change',
		'.directorist-instant-search .listing-with-sidebar select',
		debounce( function ( e ) {
			e.preventDefault();
			if (!$(this).val()) {
				return; // Skip search if the value is empty
			}

			e.preventDefault();
			var searchElm =
<<<<<<< HEAD
				$( this ).val() && $( this ).closest( '.listing-with-sidebar' );
			filterListing( searchElm );
		}, 250 )
	);

	// select on change with value - searching
	$( 'body' ).on(
=======
				$(this).val() && $(this).closest('.listing-with-sidebar');

			// Instant search with required value
			performInstantSearchWithRequiredValue(searchElm);
		}, 250)
	);
	
	// sidebar on change searching - color
	window.addEventListener('directorist-color-changed',
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
>>>>>>> development
		'click',
		'.directorist-instant-search .listing-with-sidebar .directorist-filter-location-icon',
		debounce( function ( e ) {
			e.preventDefault();
<<<<<<< HEAD
			var searchElm = $( this ).closest( '.listing-with-sidebar' );
			filterListing( searchElm );
		}, 1000 )
=======
			var searchElm = $(this).closest('.listing-with-sidebar');

			// Instant search with required value
			performInstantSearchWithRequiredValue(searchElm);
		}, 1000)
>>>>>>> development
	);

	// Clear Input Value
	$( 'body' ).on(
		'click',
<<<<<<< HEAD
		'.directorist-instant-search .directorist-search-field__btn--clear',
		function ( e ) {
			let inputValue = $( this )
				.closest( '.directorist-search-field' )
				.find(
					'input:not([type="checkbox"]):not([type="radio"]), select'
				)
				.val( '' );

			if ( inputValue ) {
				let searchElm = $(
					document.querySelector(
						'.directorist-instant-search .listing-with-sidebar form'
					)
				);
				if ( searchElm ) {
					filterListing( searchElm );
				}
=======
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
			.find('input:not([type="checkbox"]):not([type="radio"]):not(.wp-picker-clear), select')
			.val('');

			// Clear checkboxes
			$searchField.find('input[type="checkbox"]').prop('checked', false);

			// Clear radio buttons
			$searchField.find('input[type="radio"]').prop('checked', false);

			// Proceed if form exists
			if ($form.length) {
				performInstantSearchWithRequiredValue($form);
>>>>>>> development
			}
		}
	);

<<<<<<< HEAD
	if ( $( '.directorist-instant-search' ).length === 0 ) {
		$( 'body' ).on(
=======
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
>>>>>>> development
			'submit',
			'.listing-with-sidebar .directorist-basic-search, .listing-with-sidebar .directorist-advanced-search',
			function ( e ) {
				e.preventDefault();
				let basic_data = $(
					'.listing-with-sidebar .directorist-basic-search'
				).serialize();
				let advanced_data = $(
					'.listing-with-sidebar .directorist-advanced-search'
				).serialize();
				let action_value = $( '.directorist-advanced-search' ).attr(
					'action'
				);
				let url = action_value + '?' + basic_data + '&' + advanced_data;

				window.location.href = url;
			}
		);
	}

<<<<<<< HEAD
	window.addEventListener( 'load', function () {
		debounce( initObserver(), 250 );

		singleCategoryLocationInit();
	} );
} )( jQuery );
=======
	// Prevent disabled links from being clicked
	$('body').on('click', '.disabled-link', function (e) {
		e.preventDefault();
	});

	// Prevent default action for dropdown links
	$('.directorist-instant-search .directorist-dropdown__links__single-js').off('click');

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
>>>>>>> development
