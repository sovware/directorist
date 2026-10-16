(function ($) {
	'use strict';

	var config = window.directoristAiSetup || {};
	var i18n = config.i18n || {};
	var maxRegenerations = 3;
	var progressValue = 0;
	var storageConfig = config.storage || {};
	var storageKey =
		storageConfig.key || 'directorist-ai-setup-wizard-session-v1';
	var storageTtl = Math.max(
		0,
		parseInt(storageConfig.ttl, 10) || 24 * 60 * 60
	);
	var currentScreen = 'prompt';
	var pendingOperation = '';
	var persistenceEnabled = true;

	var state = {
		prompt: '',
		setup: null,
		selected: [],
		regenerateCount: 0,
	};

	var presets = {
		restaurant:
			'Create a restaurant directory for local dining spots with cuisine, price range, location, contact, menu link, photos, and booking information.',
		job: 'Create a job board for tech roles with job title, company, salary range, location, job type, skills, application link, and deadline.',
		realestate:
			'Create a real estate directory for apartments and homes with price, property type, bedrooms, bathrooms, area, address, map, photos, and contact details.',
		classified:
			'Create a classifieds marketplace for local buy and sell listings with price, condition, category, location, seller contact, photos, and product details.',
		service:
			'Create a service marketplace for local professionals with service type, pricing, service area, experience, portfolio link, contact, and photos.',
		local:
			'Create a local business directory with categories, address, phone, email, website, map, photos, social links, and short business details.',
		automotive:
			'Create a car listings directory with make, model, year, mileage, price, condition, location, seller contact, photos, and video.',
		hotel:
			'Create a hotel directory with room type, price range, amenities, location, address, phone, website, photos, and booking link.',
		medical:
			'Create a medical directory for clinics and doctors with specialty, location, address, phone, website, appointment link, insurance options, and photos.',
		legal:
			'Create a legal directory for lawyers and law firms with practice area, location, consultation type, phone, email, website, and profile details.',
	};

	var typeLabels = {
		image_upload: 'Images',
		social_info: 'Social Info',
		terms_privacy: 'Terms',
		color_picker: 'Color',
		phone2: 'Phone 2',
		html: 'HTML',
		url: 'URL',
	};

	var presetTypes = {
		title: true,
		description: true,
		tagline: true,
		pricing: true,
		excerpt: true,
		location: true,
		tag: true,
		category: true,
		map: true,
		address: true,
		zip: true,
		phone: true,
		phone2: true,
		fax: true,
		email: true,
		website: true,
		social_info: true,
		image_upload: true,
		video: true,
		terms_privacy: true,
	};

	var typeAliases = {
		dropdown: 'select',
		long_text: 'textarea',
		multi_line: 'textarea',
		wp_editor: 'html',
		image: 'image_upload',
		images: 'image_upload',
		photos: 'image_upload',
		photo: 'image_upload',
		file_upload: 'file',
		postal_code: 'zip',
		postcode: 'zip',
		locations: 'location',
		categories: 'category',
		tags: 'tag',
		phone_number: 'phone',
		socialinfo: 'social_info',
		business_hours: '',
		opening_hours: '',
		hours: '',
	};

	function t(key, fallback) {
		return i18n[key] || fallback;
	}

	function sprintf(template, value) {
		return (template || '').replace('%d', value);
	}

	function getMessage(response, fallback) {
		var data = response && response.data ? response.data : response;

		if (data && data.message) {
			return data.message;
		}

		if (data && data.data && data.data.message) {
			return data.data.message;
		}

		if (response && response.responseJSON) {
			return getMessage(response.responseJSON, fallback);
		}

		return fallback;
	}

	function getSessionStorage() {
		try {
			var storage = window.sessionStorage;
			var testKey = storageKey + '-test';

			storage.setItem(testKey, '1');
			storage.removeItem(testKey);

			return storage;
		} catch (error) {
			return null;
		}
	}

	function clearPersistedState() {
		var storage = getSessionStorage();

		if (storage) {
			storage.removeItem(storageKey);
		}
	}

	function persistState() {
		var storage = getSessionStorage();
		var snapshot;

		if (!persistenceEnabled || !storage) {
			return;
		}

		snapshot = {
			version: 1,
			savedAt: Date.now(),
			screen: currentScreen,
			pendingOperation: pendingOperation,
			fieldsEditorOpen: $('#directorist-ai-setup-fields-editor').hasClass(
				'open'
			),
			state: {
				prompt: state.prompt,
				setup: state.setup,
				selected: state.selected,
				regenerateCount: state.regenerateCount,
			},
		};

		try {
			storage.setItem(storageKey, JSON.stringify(snapshot));
		} catch (error) {
			// Storage can be disabled or full. The wizard must remain usable without it.
		}
	}

	function readPersistedState() {
		var storage = getSessionStorage();
		var snapshot;

		if (!storage) {
			return null;
		}

		try {
			snapshot = JSON.parse(storage.getItem(storageKey) || 'null');
		} catch (error) {
			clearPersistedState();
			return null;
		}

		if (
			!snapshot ||
			snapshot.version !== 1 ||
			!snapshot.savedAt ||
			(storageTtl && Date.now() - snapshot.savedAt > storageTtl * 1000) ||
			!snapshot.state ||
			typeof snapshot.state !== 'object'
		) {
			clearPersistedState();
			return null;
		}

		return snapshot;
	}

	function restorePersistedState() {
		var snapshot = readPersistedState();
		var restoredState;
		var fieldCount;
		var interruptedOperation;
		var restoredScreen;

		if (!snapshot) {
			return null;
		}

		restoredState = snapshot.state;
		state.prompt =
			typeof restoredState.prompt === 'string'
				? restoredState.prompt
				: '';
		state.setup = restoredState.setup
			? normalizeSetup(restoredState.setup)
			: null;

		if (state.setup) {
			if (
				Object.prototype.hasOwnProperty.call(
					restoredState.setup,
					'directory_name'
				)
			) {
				state.setup.directory_name = $.trim(
					restoredState.setup.directory_name || ''
				);
			}

			if (Array.isArray(restoredState.setup.categories)) {
				state.setup.categories = restoredState.setup.categories
					.map(function (category) {
						return $.trim(category || '');
					})
					.filter(Boolean);
			}
		}

		fieldCount = state.setup ? state.setup.fields.length : 0;
		state.selected = Array.isArray(restoredState.selected)
			? restoredState.selected.filter(function (index) {
					return (
						typeof index === 'number' &&
						isFinite(index) &&
						Math.floor(index) === index &&
						index >= 0 &&
						index < fieldCount &&
						!isLockedField(state.setup.fields[index])
					);
				})
			: [];
		state.regenerateCount = Math.max(
			0,
			Math.min(
				maxRegenerations,
				parseInt(restoredState.regenerateCount, 10) || 0
			)
		);

		interruptedOperation = snapshot.pendingOperation || '';
		restoredScreen =
			snapshot.screen === 'summary' && state.setup ? 'summary' : 'prompt';

		if (
			interruptedOperation === 'regenerate' ||
			interruptedOperation === 'launch'
		) {
			restoredScreen = state.setup ? 'summary' : 'prompt';
		}

		currentScreen = restoredScreen;
		pendingOperation = '';

		return {
			screen: restoredScreen,
			fieldsEditorOpen:
				!!snapshot.fieldsEditorOpen && restoredScreen === 'summary',
			notice:
				interruptedOperation === 'launch'
					? t(
							'launchInterrupted',
							'The launch was interrupted by the reload. Check your listings before trying again.'
						)
					: interruptedOperation
						? t(
								'requestInterrupted',
								'The request was interrupted by the reload. Your saved progress has been restored.'
							)
						: '',
		};
	}

	function fieldTypeLabel(type) {
		type = type || 'text';

		if (typeLabels[type]) {
			return typeLabels[type];
		}

		return type.replace(/_/g, ' ').replace(/\b\w/g, function (letter) {
			return letter.toUpperCase();
		});
	}

	function isLockedField(field) {
		return (
			field && (field.type === 'title' || field.type === 'description')
		);
	}

	function isSecondaryPhoneLabel(label) {
		return /\b(phone\s*2|phone2|alternative\s+phone|alternate\s+phone|secondary\s+phone|second\s+phone|additional\s+phone|other\s+phone|backup\s+phone|alternative\s+contact(?:\s+number)?|alternate\s+contact(?:\s+number)?|secondary\s+contact(?:\s+number)?)\b/i.test(
			label || ''
		);
	}

	function primaryPhoneLabel(label) {
		return isSecondaryPhoneLabel(label) ? 'Phone' : label;
	}

	function normalizeFieldType(type, label) {
		var rawType = $.trim(type || 'text')
			.toLowerCase()
			.replace(/[\s-]+/g, '_');
		var labelText = $.trim(label || '');
		var labelKey = labelText.toLowerCase();
		var socialPattern =
			/\b(social|facebook|twitter|x profile|instagram|linkedin|youtube|tiktok|pinterest|snapchat)\b/i;

		rawType = Object.prototype.hasOwnProperty.call(typeAliases, rawType)
			? typeAliases[rawType]
			: rawType;

		if (socialPattern.test(labelText + ' ' + rawType)) {
			return 'social_info';
		}

		if (/\b(image|images|photo|photos|gallery|logo)\b/i.test(labelKey)) {
			return 'image_upload';
		}

		if (/\b(website|site url|business url|company url)\b/i.test(labelKey)) {
			return 'website';
		}

		if (/\b(email|e-mail)\b/i.test(labelKey)) {
			return 'email';
		}

		if (isSecondaryPhoneLabel(labelText + ' ' + rawType)) {
			return 'phone2';
		}

		if (/\b(phone|telephone|contact number|mobile)\b/i.test(labelKey)) {
			return 'phone';
		}

		return rawType;
	}

	function fieldsHaveType(fields, type) {
		return (fields || []).some(function (field) {
			return field && field.type === type;
		});
	}

	function countFieldsOfType(fields, type) {
		var count = 0;

		(fields || []).forEach(function (field) {
			if (!field || typeof field !== 'object') {
				return;
			}

			if (
				normalizeFieldType(field.type || '', field.label || '') === type
			) {
				count++;
			}
		});

		return count;
	}

	function normalizeDependentPresetFields(fields) {
		var hasPhone = fieldsHaveType(fields, 'phone');

		return (fields || []).map(function (field) {
			var nextField = $.extend({}, field);

			if (nextField.type === 'phone2' && !hasPhone) {
				nextField.type = 'phone';
				nextField.label = 'Phone';
				hasPhone = true;
				return nextField;
			}

			if (
				nextField.type === 'phone' &&
				isSecondaryPhoneLabel(nextField.label)
			) {
				nextField.label = 'Phone';
			}

			return nextField;
		});
	}

	function findPhoneLikeFieldIndex(fields) {
		var result = null;

		(fields || []).some(function (field, index) {
			var type;

			if (!field || typeof field !== 'object') {
				return false;
			}

			type = normalizeFieldType(field.type || '', field.label || '');

			if (
				type === 'phone' ||
				type === 'phone2' ||
				/\b(phone|telephone|contact number|mobile)\b/i.test(
					field.label || ''
				)
			) {
				result = index;
				return true;
			}

			return false;
		});

		return result;
	}

	function preserveRegeneratedSelectedFieldContext(
		newFields,
		existingFields,
		selectedFields
	) {
		var selectedField;
		var selectedType;
		var existingPhoneCount;
		var selectedPhoneCount;
		var hasUnselectedPhone;
		var targetType;
		var targetLabel;
		var targetIndex;

		if (
			!Array.isArray(selectedFields) ||
			selectedFields.length !== 1 ||
			!newFields.length
		) {
			return newFields;
		}

		selectedField = selectedFields[0];
		selectedType = normalizeFieldType(
			selectedField.type || '',
			selectedField.label || ''
		);

		if (selectedType !== 'phone' && selectedType !== 'phone2') {
			return newFields;
		}

		existingPhoneCount = countFieldsOfType(existingFields, 'phone');
		selectedPhoneCount = countFieldsOfType(selectedFields, 'phone');
		hasUnselectedPhone = existingPhoneCount > selectedPhoneCount;
		targetType =
			selectedType === 'phone2' && hasUnselectedPhone
				? 'phone2'
				: 'phone';
		targetLabel =
			targetType === 'phone2'
				? selectedField.label || 'Alternative Phone'
				: primaryPhoneLabel(selectedField.label || 'Phone');
		targetIndex = findPhoneLikeFieldIndex(newFields);

		if (targetIndex === null) {
			targetIndex = 0;
		}

		newFields[targetIndex] = $.extend({}, newFields[targetIndex], {
			type: targetType,
			label: targetLabel,
		});

		return normalizeDependentPresetFields(newFields);
	}

	function normalizeFields(fields) {
		var normalized = [];
		var seenLocked = {};

		if (!Array.isArray(fields)) {
			return normalized;
		}

		fields.forEach(function (field) {
			var label;
			var type;

			if (!field || typeof field !== 'object') {
				return;
			}

			label = $.trim(field.label || field.name || '');
			type = normalizeFieldType(field.type || 'text', label);

			if (!label || /hours/i.test(label) || /hours/i.test(type)) {
				return;
			}

			if (presetTypes[type] && seenLocked[type]) {
				return;
			}

			if (presetTypes[type]) {
				seenLocked[type] = true;
			}

			normalized.push({
				label: label,
				type: type,
				group: $.trim(
					field.group || field.group_name || 'General Information'
				),
				options: Array.isArray(field.options) ? field.options : [],
			});
		});

		return normalizeDependentPresetFields(normalized);
	}

	function dedupeLockedPresetFields(fields) {
		var seenLocked = {};

		return normalizeDependentPresetFields(
			(fields || []).filter(function (field) {
				var type = field && field.type;

				if (!presetTypes[type]) {
					return true;
				}

				if (seenLocked[type]) {
					return false;
				}

				seenLocked[type] = true;
				return true;
			})
		);
	}

	function normalizeSetup(setup) {
		setup = setup && typeof setup === 'object' ? setup : {};

		var fields = normalizeFields(setup.fields || []);
		var categories = Array.isArray(setup.categories)
			? setup.categories
			: [];

		categories = categories
			.map(function (category) {
				return $.trim(category || '');
			})
			.filter(Boolean);

		if (!categories.length) {
			categories = ['General'];
		}

		return {
			directory_name: $.trim(
				setup.directory_name ||
					setup.name ||
					setup.type ||
					'Business Directory'
			),
			categories: categories,
			default_address: $.trim(
				setup.default_address ||
					setup.default_location ||
					setup.location ||
					''
			),
			fields: fields,
			monetization: !!setup.monetization || !!setup.payment,
			data_sharing:
				typeof setup.data_sharing === 'undefined'
					? true
					: !!setup.data_sharing,
			demo_content:
				typeof setup.demo_content === 'undefined'
					? true
					: !!setup.demo_content,
		};
	}

	function ajax(action, data) {
		data = data || {};
		data.action = action;
		data.directorist_nonce = config.nonce || '';

		return $.ajax({
			url: config.ajaxUrl,
			method: 'POST',
			data: data,
		});
	}

	function prefersReducedMotion() {
		return (
			window.matchMedia &&
			window.matchMedia('(prefers-reduced-motion: reduce)').matches
		);
	}

	function setProgress(width, duration, callback) {
		var target = Math.max(0, Math.min(100, width));
		var $progress = $('#directorist-ai-setup-progress');
		var $track = $('#directorist-ai-setup-progress-track');
		var animationDuration = prefersReducedMotion() ? 0 : duration || 0;

		$progress.stop(true, false);

		if (!animationDuration) {
			progressValue = target;
			$progress.css('width', target + '%');
			$track.attr('aria-valuenow', Math.round(target));

			if (callback) {
				callback();
			}

			return;
		}

		$progress.animate(
			{ width: target + '%' },
			{
				duration: animationDuration,
				easing: 'linear',
				step: function (value) {
					progressValue = value;
					$track.attr('aria-valuenow', Math.round(value));
				},
				complete: function () {
					progressValue = target;
					$track.attr('aria-valuenow', Math.round(target));

					if (callback) {
						callback();
					}
				},
			}
		);
	}

	function startGenerationProgress() {
		setProgress(5, 220, function () {
			setProgress(68, 12000);
		});
	}

	function startLaunchProgress() {
		setProgress(Math.max(progressValue, 78), 180, function () {
			setProgress(96, 10000);
		});
	}

	function showScreen(screen, skipPersist) {
		currentScreen = screen;

		$(
			'#directorist-ai-setup-screen-prompt, #directorist-ai-setup-loading, #directorist-ai-setup-screen-summary, #directorist-ai-setup-done'
		).addClass('directorist-ai-setup__hidden');

		$('.directorist-ai-setup__notice').remove();

		if (screen === 'prompt') {
			$('#directorist-ai-setup-screen-prompt').removeClass(
				'directorist-ai-setup__hidden'
			);
			$('#directorist-ai-setup-prompt').trigger('focus');
		}

		if (screen === 'loading') {
			$('#directorist-ai-setup-loading').removeClass(
				'directorist-ai-setup__hidden'
			);
		}

		if (screen === 'summary') {
			$('#directorist-ai-setup-screen-summary').removeClass(
				'directorist-ai-setup__hidden'
			);
		}

		if (screen === 'done') {
			$('#directorist-ai-setup-done').removeClass(
				'directorist-ai-setup__hidden'
			);
		}

		if (!skipPersist) {
			persistState();
		}
	}

	function showNotice(message) {
		var $screen = $(
			'.directorist-ai-setup__screen:not(.directorist-ai-setup__hidden)'
		);

		$('.directorist-ai-setup__notice').remove();

		if (!$screen.length) {
			$screen = $('.directorist-ai-setup__page');
		}

		$('<div />', {
			class: 'directorist-ai-setup__notice',
			role: 'alert',
			text: message,
		}).prependTo($screen);
	}

	function setButtonLoading($button, loading, label) {
		if (!$button.length) {
			return;
		}

		if (loading) {
			$button.data('label', $.trim($button.text()));
			$button.prop('disabled', true).addClass('is-loading');
			$button
				.contents()
				.filter(function () {
					return this.nodeType === 3;
				})
				.first()
				.replaceWith(label + ' ');
			return;
		}

		$button.prop('disabled', false).removeClass('is-loading');
		$button
			.contents()
			.filter(function () {
				return this.nodeType === 3;
			})
			.first()
			.replaceWith(($button.data('label') || '') + ' ');
	}

	function setLocationStatus(type, message) {
		$('#directorist-ai-setup-location-status')
			.removeClass('is-success is-warning')
			.addClass(type ? 'is-' + type : '')
			.text(message || '');
	}

	function setLocationDetecting(detecting) {
		var $button = $('#directorist-ai-setup-location-detect');
		var label = detecting
			? t('detectingLocation', 'Detecting your current location...')
			: t('detectLocation', 'Use my current location');

		$button
			.prop('disabled', detecting)
			.toggleClass('is-loading', detecting)
			.attr('aria-busy', detecting ? 'true' : 'false')
			.attr('aria-label', label)
			.attr('title', label);
		$('#directorist-ai-setup-location').attr(
			'aria-busy',
			detecting ? 'true' : 'false'
		);
	}

	function locationErrorMessage(error) {
		if (window.isSecureContext === false) {
			return t(
				'locationInsecure',
				'Location detection needs HTTPS or localhost. Open this admin page over HTTPS and try again.'
			);
		}

		if (!error || typeof error.code === 'undefined') {
			return t(
				'locationUnavailable',
				'Your current location is unavailable. Please try again.'
			);
		}

		if (error.code === error.PERMISSION_DENIED) {
			return t(
				'locationBlocked',
				'Location access is blocked. Allow it from your browser address bar, then try again.'
			);
		}

		if (error.code === error.TIMEOUT) {
			return t(
				'locationTimeout',
				'Location detection timed out. Please try again.'
			);
		}

		return t(
			'locationUnavailable',
			'Your current location is unavailable. Please try again.'
		);
	}

	function applyDetectedLocation(address, type, message) {
		$('#directorist-ai-setup-location').val(address).trigger('input');
		setLocationStatus(type, message);
	}

	function resolveCurrentAddress(latitude, longitude) {
		var coordinateAddress =
			latitude.toFixed(6) + ', ' + longitude.toFixed(6);

		ajax(config.actions.geocode, {
			latitude: latitude,
			longitude: longitude,
		})
			.done(function (response) {
				var address =
					response && response.success && response.data
						? $.trim(response.data.address || '')
						: '';

				if (!address) {
					applyDetectedLocation(
						coordinateAddress,
						'warning',
						t(
							'locationFallback',
							'Location detected, but the exact address could not be found. Coordinates were added instead.'
						)
					);
					return;
				}

				applyDetectedLocation(
					address,
					'success',
					t('locationDetected', 'Current address detected.')
				);
			})
			.fail(function () {
				applyDetectedLocation(
					coordinateAddress,
					'warning',
					t(
						'locationFallback',
						'Location detected, but the exact address could not be found. Coordinates were added instead.'
					)
				);
			})
			.always(function () {
				setLocationDetecting(false);
			});
	}

	function detectCurrentLocation() {
		setLocationStatus('', '');

		if (!navigator.geolocation) {
			setLocationStatus(
				'warning',
				t(
					'locationUnsupported',
					'Your browser does not support location detection.'
				)
			);
			return;
		}

		if (window.isSecureContext === false) {
			setLocationStatus('warning', locationErrorMessage());
			return;
		}

		setLocationDetecting(true);
		navigator.geolocation.getCurrentPosition(
			function (position) {
				resolveCurrentAddress(
					position.coords.latitude,
					position.coords.longitude
				);
			},
			function (error) {
				setLocationDetecting(false);
				setLocationStatus('warning', locationErrorMessage(error));
			},
			{
				enableHighAccuracy: true,
				timeout: 10000,
				maximumAge: 60000,
			}
		);
	}

	function updateGenerateState() {
		var prompt = $.trim($('#directorist-ai-setup-prompt').val() || '');
		$('#directorist-ai-setup-generate').prop('disabled', prompt.length < 5);
	}

	function updateRegenerateState() {
		var remaining = maxRegenerations - state.regenerateCount;
		var canRegenerate = state.selected.length > 0 && remaining > 0;

		$('#directorist-ai-setup-regenerate').prop('disabled', !canRegenerate);
		$('#directorist-ai-setup-regenerate-label').text(
			t('regenerate', 'Regenerate')
		);
		$('#directorist-ai-setup-regenerate-note').text(
			remaining > 0
				? sprintf(
						t(
							'regenerateNote',
							'You can regenerate fields %d more times.'
						),
						remaining
					)
				: t('regenerateDone', 'Regeneration limit reached.')
		);
	}

	function renderCategories(focusAddButton) {
		var $wrap = $('#directorist-ai-setup-categories');
		$wrap.empty();

		state.setup.categories.forEach(function (category, index) {
			var $tag = $('<span />', {
				class: 'directorist-ai-setup__tag',
			});

			$('<span />', {
				text: category,
			}).appendTo($tag);

			$('<button />', {
				type: 'button',
				class: 'directorist-ai-setup__tag-remove',
				'aria-label': 'Remove ' + category,
				text: 'x',
			})
				.data('index', index)
				.appendTo($tag);

			$tag.appendTo($wrap);
		});

		var $addButton = $('<button />', {
			type: 'button',
			class: 'directorist-ai-setup__tag-add',
			text: t('addCategory', '+ Add category'),
		}).appendTo($wrap);

		if (focusAddButton) {
			$addButton.trigger('focus');
		}
	}

	function openCategoryInput($button) {
		var $entry = $('<span />', {
			class: 'directorist-ai-setup__tag-entry',
		});
		var $input = $('<input />', {
			type: 'text',
			class: 'directorist-ai-setup__tag-input',
			maxlength: 80,
			autocomplete: 'off',
			placeholder: t('categoryName', 'Category name'),
			'aria-label': t('categoryName', 'Category name'),
			'aria-describedby': 'directorist-ai-setup-category-feedback',
			'aria-keyshortcuts': 'Enter Escape',
		});
		var $feedback = $('<span />', {
			class: 'directorist-ai-setup__tag-feedback',
			id: 'directorist-ai-setup-category-feedback',
			'aria-live': 'polite',
		});

		$entry.append($input, $feedback);
		$button.replaceWith($entry);
		$input.trigger('focus');
	}

	function addCategoryFromInput($input) {
		var category = $.trim($input.val() || '');
		var duplicate = state.setup.categories.some(
			function (existingCategory) {
				return (
					$.trim(String(existingCategory)).toLowerCase() ===
					category.toLowerCase()
				);
			}
		);
		var $feedback = $input.siblings('.directorist-ai-setup__tag-feedback');

		if (!category) {
			$input.addClass('is-invalid').attr('aria-invalid', 'true');
			$feedback.text(t('categoryRequired', 'Enter a category name.'));
			return;
		}

		if (duplicate) {
			$input
				.addClass('is-invalid')
				.attr('aria-invalid', 'true')
				.trigger('select');
			$feedback.text(
				t('categoryExists', 'This category has already been added.')
			);
			return;
		}

		state.setup.categories.push(category);
		renderCategories(true);
		persistState();
	}

	function renderFieldsSummary() {
		var fields = state.setup.fields || [];
		var names = fields.map(function (field) {
			return field.label;
		});

		$('#directorist-ai-setup-fields-count').text(
			fields.length
				? sprintf(t('fieldCount', '%d fields selected'), fields.length)
				: t('noFields', 'No fields selected yet.')
		);

		$('#directorist-ai-setup-fields-preview').text(
			names.slice(0, 8).join(', ')
		);
	}

	function renderFields() {
		var $wrap = $('#directorist-ai-setup-fields');
		$wrap.empty();

		(state.setup.fields || []).forEach(function (field, index) {
			var selected = state.selected.indexOf(index) !== -1;
			var locked = isLockedField(field);
			var $row = $('<div />', {
				class:
					'directorist-ai-setup__field-row' +
					(selected ? ' selected' : ''),
			}).attr('data-index', index);

			$('<input />', {
				type: 'checkbox',
				class: 'directorist-ai-setup__field-select',
				checked: selected,
				disabled: locked,
			}).appendTo($row);

			$('<input />', {
				type: 'text',
				class: 'directorist-ai-setup__field-name',
				value: field.label,
				'aria-label': field.label,
			}).appendTo($row);

			$('<span />', {
				class: 'directorist-ai-setup__field-type',
				text: fieldTypeLabel(field.type),
			}).appendTo($row);

			$('<button />', {
				type: 'button',
				class: 'directorist-ai-setup__field-remove',
				'aria-label': 'Remove ' + field.label,
				text: 'x',
				disabled: locked,
			}).appendTo($row);

			$row.appendTo($wrap);
		});

		updateRegenerateState();
	}

	function renderSummary() {
		$('#directorist-ai-setup-name').val(state.setup.directory_name);
		$('#directorist-ai-setup-location').val(state.setup.default_address);
		$('#directorist-ai-setup-money').prop(
			'checked',
			!!state.setup.monetization
		);
		$('#directorist-ai-setup-data-sharing').prop(
			'checked',
			!!state.setup.data_sharing
		);
		$('#directorist-ai-setup-demo-content').prop(
			'checked',
			!!state.setup.demo_content
		);
		setLocationStatus('', '');
		setLocationDetecting(false);

		renderCategories();
		renderFieldsSummary();
		renderFields();
	}

	function syncSetupFromForm() {
		if (!state.setup) {
			return;
		}

		state.setup.directory_name = $.trim(
			$('#directorist-ai-setup-name').val() || ''
		);
		state.setup.default_address = $.trim(
			$('#directorist-ai-setup-location').val() || ''
		);
		state.setup.monetization = $('#directorist-ai-setup-money').is(
			':checked'
		);
		state.setup.data_sharing = $('#directorist-ai-setup-data-sharing').is(
			':checked'
		);
		state.setup.demo_content = $('#directorist-ai-setup-demo-content').is(
			':checked'
		);
		persistState();
	}

	function extractSetupPayload(response) {
		var data = response && response.data ? response.data : response || {};
		var payload = data.setup || data.response || data;

		if (payload && payload.response) {
			payload = payload.response;
		}

		return payload;
	}

	function extractFieldsPayload(response) {
		var data = response && response.data ? response.data : response || {};
		var payload = data.fields || data.response || data;

		if (payload && payload.fields) {
			payload = payload.fields;
		}

		return payload;
	}

	function generateSetup() {
		var prompt = $.trim($('#directorist-ai-setup-prompt').val() || '');
		var $button = $('#directorist-ai-setup-generate');

		if (prompt.length < 5) {
			return;
		}

		state.prompt = prompt;
		pendingOperation = 'generate';
		setButtonLoading($button, true, 'Generating...');
		showScreen('loading');
		startGenerationProgress();

		ajax(config.actions.generate, {
			prompt: prompt,
		})
			.done(function (response) {
				if (!response || !response.success) {
					pendingOperation = '';
					showScreen('prompt');
					setProgress(0, 320);
					showNotice(
						getMessage(
							response,
							t(
								'generateError',
								'Could not generate setup data. Please try again.'
							)
						)
					);
					return;
				}

				state.setup = normalizeSetup(extractSetupPayload(response));
				state.selected = [];
				state.regenerateCount = 0;
				pendingOperation = '';
				currentScreen = 'summary';
				persistState();

				setProgress(75, 480, function () {
					renderSummary();
					showScreen('summary');

					if (response.data && response.data.fallback) {
						showNotice(
							getMessage(
								response,
								t(
									'fallbackNotice',
									'AI is taking longer than expected, so we prepared a starter setup. You can edit it before launch.'
								)
							)
						);
					}
				});
			})
			.fail(function (response) {
				pendingOperation = '';
				showScreen('prompt');
				setProgress(0, 320);
				showNotice(
					getMessage(
						response,
						t(
							'generateError',
							'Could not generate setup data. Please try again.'
						)
					)
				);
			})
			.always(function () {
				setButtonLoading($button, false);
				updateGenerateState();
			});
	}

	function regenerateFields() {
		var indexes = state.selected.slice().sort(function (a, b) {
			return a - b;
		});
		var selectedFields = indexes.map(function (index) {
			return state.setup.fields[index];
		});
		var $button = $('#directorist-ai-setup-regenerate');

		if (!indexes.length || state.regenerateCount >= maxRegenerations) {
			return;
		}

		pendingOperation = 'regenerate';
		persistState();
		$button.prop('disabled', true);
		$('#directorist-ai-setup-regenerate-label').text(
			t('regenerating', 'Regenerating...')
		);

		ajax(config.actions.regenerate, {
			prompt: state.prompt,
			fields: JSON.stringify(state.setup.fields),
			selected: JSON.stringify(selectedFields),
		})
			.done(function (response) {
				var newFields;
				var firstIndex;
				var indexMap = {};
				var nextFields = [];

				if (!response || !response.success) {
					pendingOperation = '';
					persistState();
					showNotice(
						getMessage(
							response,
							t(
								'generateError',
								'Could not generate setup data. Please try again.'
							)
						)
					);
					return;
				}

				newFields = normalizeFields(extractFieldsPayload(response));
				newFields = preserveRegeneratedSelectedFieldContext(
					newFields,
					state.setup.fields,
					selectedFields
				);

				if (!newFields.length) {
					pendingOperation = '';
					persistState();
					showNotice(
						t(
							'generateError',
							'Could not generate setup data. Please try again.'
						)
					);
					return;
				}

				firstIndex = indexes[0];
				indexes.forEach(function (index) {
					indexMap[index] = true;
				});

				state.setup.fields.forEach(function (field, index) {
					if (index === firstIndex) {
						nextFields = nextFields.concat(newFields);
					}

					if (!indexMap[index]) {
						nextFields.push(field);
					}
				});

				state.setup.fields = dedupeLockedPresetFields(nextFields);
				state.selected = [];
				state.regenerateCount++;
				pendingOperation = '';

				renderFieldsSummary();
				renderFields();
				persistState();
			})
			.fail(function (response) {
				pendingOperation = '';
				persistState();
				showNotice(
					getMessage(
						response,
						t(
							'generateError',
							'Could not generate setup data. Please try again.'
						)
					)
				);
			})
			.always(function () {
				updateRegenerateState();
			});
	}

	function launchDirectory() {
		var $button = $('#directorist-ai-setup-launch');

		syncSetupFromForm();

		if (!state.setup.directory_name) {
			showNotice('Please add a directory name.');
			return;
		}

		setButtonLoading($button, true, t('launching', 'Launching...'));
		pendingOperation = 'launch';
		persistState();
		startLaunchProgress();

		ajax(config.actions.launch, {
			setup: JSON.stringify(state.setup),
		})
			.done(function (response) {
				var url;

				if (!response || !response.success) {
					pendingOperation = '';
					persistState();
					setProgress(75, 320);
					showNotice(
						getMessage(
							response,
							t(
								'launchError',
								'Could not launch your directory. Please try again.'
							)
						)
					);
					return;
				}

				url =
					response.data && response.data.url
						? response.data.url
						: config.dashboard;

				setProgress(100, 420, function () {
					persistenceEnabled = false;
					clearPersistedState();
					showScreen('done', true);

					window.setTimeout(function () {
						window.location.href = url;
					}, 900);
				});
			})
			.fail(function (response) {
				pendingOperation = '';
				persistState();
				setProgress(75, 320);
				showNotice(
					getMessage(
						response,
						t(
							'launchError',
							'Could not launch your directory. Please try again.'
						)
					)
				);
			})
			.always(function () {
				setButtonLoading($button, false);
				$button
					.contents()
					.filter(function () {
						return this.nodeType === 3;
					})
					.first()
					.replaceWith(t('launch', 'Launch my directory') + ' ');
			});
	}

	function exitSetup() {
		if (
			window.confirm(
				t(
					'exitConfirm',
					'Exit setup and go to the dashboard? Your progress will not be saved.'
				)
			)
		) {
			window.location.href =
				config.dashboard || window.ajaxurl || '/wp-admin/';
		}
	}

	$(function () {
		var $prompt = $('#directorist-ai-setup-prompt');
		var restored = restorePersistedState();

		$prompt.val(state.prompt);

		if (restored && restored.screen === 'summary' && state.setup) {
			renderSummary();
			showScreen('summary', true);
			setProgress(75);

			if (restored.fieldsEditorOpen) {
				$('#directorist-ai-setup-fields-editor').addClass('open');
			}
		} else {
			showScreen('prompt', true);
			setProgress(0);
		}

		updateGenerateState();
		persistState();

		if (restored && restored.notice) {
			showNotice(restored.notice);
		}

		$prompt.on('input', function () {
			state.prompt = $(this).val() || '';
			updateGenerateState();
			persistState();
		});

		$('#directorist-ai-setup-generate').on('click', generateSetup);
		$('#directorist-ai-setup-back').on('click', function () {
			showScreen('prompt');
			setProgress(0, 320);
		});

		$('#directorist-ai-setup-close, #directorist-ai-setup-exit').on(
			'click',
			exitSetup
		);

		$('#directorist-ai-setup-chips').on(
			'click',
			'.directorist-ai-setup__chip',
			function () {
				var preset = $(this).data('preset');
				$prompt
					.val(presets[preset] || $(this).text())
					.trigger('input')
					.focus();
			}
		);

		$('#directorist-ai-setup-categories')
			.on('click', '.directorist-ai-setup__tag-remove', function () {
				var index = $(this).data('index');
				state.setup.categories.splice(index, 1);
				renderCategories();
				persistState();
			})
			.on('click', '.directorist-ai-setup__tag-add', function () {
				openCategoryInput($(this));
			})
			.on('input', '.directorist-ai-setup__tag-input', function () {
				$(this)
					.removeClass('is-invalid')
					.removeAttr('aria-invalid')
					.siblings('.directorist-ai-setup__tag-feedback')
					.text('');
			})
			.on(
				'keydown',
				'.directorist-ai-setup__tag-input',
				function (event) {
					if (event.key === 'Enter') {
						event.preventDefault();
						addCategoryFromInput($(this));
						return;
					}

					if (event.key === 'Escape') {
						event.preventDefault();
						renderCategories(true);
					}
				}
			)
			.on('blur', '.directorist-ai-setup__tag-input', function () {
				var input = this;

				window.setTimeout(function () {
					if (document.documentElement.contains(input)) {
						renderCategories();
					}
				}, 0);
			});

		$('#directorist-ai-setup-fields-edit').on('click', function () {
			$('#directorist-ai-setup-fields-editor').addClass('open');
			persistState();
		});

		$('#directorist-ai-setup-fields-done').on('click', function () {
			$('#directorist-ai-setup-fields-editor').removeClass('open');
			renderFieldsSummary();
			persistState();
		});

		$('#directorist-ai-setup-regenerate').on('click', regenerateFields);
		$('#directorist-ai-setup-location-detect').on(
			'click',
			detectCurrentLocation
		);
		$('#directorist-ai-setup-launch').on('click', launchDirectory);

		$('#directorist-ai-setup-name, #directorist-ai-setup-location').on(
			'input',
			syncSetupFromForm
		);

		$('#directorist-ai-setup-location').on('input', function (event) {
			if (event.originalEvent) {
				setLocationStatus('', '');
			}
		});

		$(
			'#directorist-ai-setup-money, #directorist-ai-setup-data-sharing, #directorist-ai-setup-demo-content'
		).on('change', syncSetupFromForm);

		$('#directorist-ai-setup-fields')
			.on('change', '.directorist-ai-setup__field-select', function () {
				var $row = $(this).closest('.directorist-ai-setup__field-row');
				var index = parseInt($row.attr('data-index'), 10);
				var selectedIndex = state.selected.indexOf(index);

				if ($(this).is(':checked') && selectedIndex === -1) {
					state.selected.push(index);
				}

				if (!$(this).is(':checked') && selectedIndex !== -1) {
					state.selected.splice(selectedIndex, 1);
				}

				$row.toggleClass('selected', $(this).is(':checked'));
				updateRegenerateState();
				persistState();
			})
			.on('input', '.directorist-ai-setup__field-name', function () {
				var index = parseInt(
					$(this)
						.closest('.directorist-ai-setup__field-row')
						.attr('data-index'),
					10
				);

				if (state.setup && state.setup.fields[index]) {
					state.setup.fields[index].label = $.trim(
						$(this).val() || ''
					);
					renderFieldsSummary();
					persistState();
				}
			})
			.on('click', '.directorist-ai-setup__field-remove', function () {
				var index;

				if ($(this).prop('disabled')) {
					return;
				}

				index = parseInt(
					$(this)
						.closest('.directorist-ai-setup__field-row')
						.attr('data-index'),
					10
				);

				state.setup.fields.splice(index, 1);
				state.selected = [];
				renderFieldsSummary();
				renderFields();
				persistState();
			});

		$(window).on('pagehide', function () {
			if (state.setup && currentScreen === 'summary') {
				syncSetupFromForm();
			} else {
				state.prompt = $prompt.val() || '';
				persistState();
			}
		});
	});
})(jQuery);
