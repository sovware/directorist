import debounce from '../../global/components/debounce';

document.addEventListener('DOMContentLoaded', () => {
	const $ = jQuery;

	// ─── Shared media upload state ────────────────────────────────────────────────
	let frame;
	let selection;
	let prv_image;
	let prv_url;
	let prv_img_url;
	const multiple_image = true;

	// ─── Utility ──────────────────────────────────────────────────────────────────
	function toggle_section(show_if_value, subject_elm, terget_elm) {
		if (show_if_value === subject_elm.val()) {
			terget_elm.show();
		} else {
			terget_elm.hide();
		}
	}

	// ─── Listing image uploader ───────────────────────────────────────────────────
	$('body').on('click', '#listing_image_btn', function (event) {
		event.preventDefault();

		if (frame) {
			frame.open();
			return;
		}

		frame = wp.media({
			title:   directorist_admin.i18n_text.upload_image,
			button:  { text: directorist_admin.i18n_text.choose_image },
			library: { type: 'image' },
			multiple: multiple_image,
		});

		frame.on('select', function () {
			selection = multiple_image
				? frame.state().get('selection').toJSON()
				: frame.state().get('selection').first().toJSON();

			let data = '';

			if ($('.single_attachment').length === 0) {
				$('.listing-img-container').html('');
			}

			if (multiple_image) {
				$(selection).each(function () {
					if (this.type === 'image') {
						data += '<div class="single_attachment">';
						data += `<input class="listing_image_attachment" name="listing_img[]" type="hidden" value="${this.id}">`;
						data += `<img style="width:100%;height:100%;" src="${this.url}" alt="Listing Image" />`;
						data += `<span class="remove_image fa fa-times" title="Remove it"></span></div>`;
					}
				});
			} else {
				if (selection.type === 'image') {
					data += '<div class="single_attachment">';
					data += `<input class="listing_image_attachment" name="listing_img[]" type="hidden" value="${selection.id}">`;
					data += `<img style="width:100%;height:100%;" src="${selection.url}" alt="Listing Image" />`;
					data += `<span class="remove_image fa fa-times" title="Remove it"></span></div>`;
				}
			}

			if (multiple_image) {
				$('.listing-img-container').append(data);
			} else {
				$('.listing-img-container').html(data);
			}

			$('#delete-custom-img').removeClass('hidden');
		});

		frame.open();
	});

	$('body').on('click', '#delete-custom-img', function (event) {
		event.preventDefault();
		$('.listing-img-container').html(
			`<img src="${directorist_admin.assets_path}images/no-image.png" alt="Listing Image" />`
		);
		$(this).addClass('hidden');
	});

	$(document).on('click', '.remove_image', function (e) {
		e.preventDefault();
		$(this).parent().remove();
		if ($('.single_attachment').length === 0) {
			$('.listing-img-container').html(
				`<img src="${directorist_admin.assets_path}images/no-image.png" alt="Listing Image" />` +
				`<p>No images</p><small>(allowed formats jpeg. png. gif)</small>`
			);
			$('#delete-custom-img').addClass('hidden');
		}
	});

	// ─── Tagline / excerpt toggle ─────────────────────────────────────────────────
	const has_tagline = $('#has_tagline').val();
	const has_excerpt = $('#has_excerpt').val();
	if (has_excerpt && has_tagline) {
		$('.atbd_tagline_moto_field').fadeIn();
	} else {
		$('.atbd_tagline_moto_field').fadeOut();
	}

	$('#atbd_optional_field_check').on('change', function () {
		$(this).is(':checked')
			? $('.atbd_tagline_moto_field').fadeIn()
			: $('.atbd_tagline_moto_field').fadeOut();
	});

	// ─── Header preview image uploader (static, outside AJAX wrapper) ─────────────
	let imageUpload;

	$('.upload-header').on('click', function (element) {
		element.preventDefault();

		imageUpload = wp.media.frames.file_frame = wp.media({
			title:  directorist_admin.i18n_text.select_prv_img,
			button: { text: directorist_admin.i18n_text.insert_prv_img },
		});

		imageUpload.on('select', function () {
			prv_image   = imageUpload.state().get('selection').first().toJSON();
			prv_url     = prv_image.id;
			prv_img_url = prv_image.url;

			$('.listing_prv_img').val(prv_url);
			$('.change_listing_prv_img').attr('src', prv_img_url);
			$('.upload-header').html('Change Preview Image');
			$('.remove_prev_img').show();
		});

		imageUpload.open();
	});

	$('.remove_prev_img').on('click', function (e) {
		e.preventDefault();
		$(this).hide();
		$('.listing_prv_img').attr('value', '');
		$('.change_listing_prv_img').attr('src', '');
	});

	if ($('.change_listing_prv_img').attr('src') === '') {
		$('.remove_prev_img').hide();
	} else {
		$('.remove_prev_img').show();
	}

	// ─── Popular listing controls ─────────────────────────────────────────────────
	const avg_review   = $('#average_review_for_popular').hide();
	const logged_count = $('#views_for_popular').hide();

	function syncPopularControls(val) {
		if (val === 'average_rating') {
			avg_review.show(); logged_count.hide();
		} else if (val === 'view_count') {
			logged_count.show(); avg_review.hide();
		} else if (val === 'both_view_rating') {
			avg_review.show(); logged_count.show();
		}
	}
	syncPopularControls(
		$('#listing_popular_by select[name="listing_popular_by"]').val()
	);
	$('#listing_popular_by select[name="listing_popular_by"]').on('change', function () {
		syncPopularControls($(this).val());
	});

	// ─── Category image uploader ──────────────────────────────────────────────────
	function atbdp_render_media_uploader(page) {
		let frame;

		if (frame) { frame.open(); return; }

		frame = wp.media({
			title:    directorist_admin.i18n_text.image_uploader_title,
			multiple: false,
			library:  { type: 'image' },
			button:   { text: directorist_admin.i18n_text.choose_image },
		});

		frame.on('select', function () {
			const image = frame.state().get('selection').first().toJSON();

			if (page === 'listings') {
				const html =
					'<tr class="atbdp-image-row">' +
					'<td class="atbdp-handle"><span class="dashicons dashicons-screenoptions"></span></td>' +
					`<td class="atbdp-image"><img src="${image.url}" />` +
					`<input type="hidden" name="images[]" value="${image.id}" /></td>` +
					`<td>${image.url}<br />` +
					`<a href="post.php?post=${image.id}&action=edit" target="_blank">${atbdp.edit}</a> | ` +
					`<a href="javascript:;" class="atbdp-delete-image" data-attachment_id="${image.id}">${atbdp.delete_permanently}</a>` +
					'</td></tr>';
				$('#atbdp-images').append(html);
			} else {
				$('#atbdp-categories-image-id').val(image.id);
				$('#atbdp-categories-image-wrapper').html(
					`<img src="${image.url}" /><a href="" class="remove_cat_img"><span class="fa fa-times" title="Remove it"></span></a>`
				);
			}
		});

		frame.open();
	}

	$('#atbdp-categories-upload-image').on('click', function (e) {
		e.preventDefault();
		atbdp_render_media_uploader('categories');
	});

	$('#submit').on('click', function () {
		$('#atbdp-categories-image-wrapper img').attr('src', '');
		$('.remove_cat_img').remove();
	});

	$(document).on('click', '.remove_cat_img', function (e) {
		e.preventDefault();
		$(this).hide();
		$(this).prev('img').remove();
		$('#atbdp-categories-image-id').attr('value', '');
	});

	// ─── Announcement ─────────────────────────────────────────────────────────────
	const announcement_to               = $('select[name="announcement_to"]');
	const announcement_recepents_section = $('#announcement_recepents');
	toggle_section('selected_user', announcement_to, announcement_recepents_section);
	announcement_to.on('change', function () {
		toggle_section('selected_user', $(this), announcement_recepents_section);
	});

	const submit_button = $('#announcement_submit .vp-input ~ span');
	const form_feedback = $('#announcement_submit .field');
	form_feedback.prepend('<div class="announcement-feedback"></div>');

	let announcement_is_sending = false;

	submit_button.on('click', function () {
		if (announcement_is_sending) { console.log('Please wait...'); return; }

		const to            = $('select[name="announcement_to"]');
		const recepents     = $('select[name="announcement_recepents"]');
		const subject       = $('input[name="announcement_subject"]');
		const message       = $('textarea[name="announcement_message"]');
		const expiration    = $('input[name="announcement_expiration"]');
		const send_to_email = $('input[name="announcement_send_to_email"]');

		const fields_elm = {
			to:            { value: to.val(),            default: 'all_user' },
			recepents:     { value: recepents.val(),     default: null },
			subject:       { value: subject.val(),       default: '' },
			message:       { value: message.val(),       default: '' },
			expiration:    { value: expiration.val(),    default: 3 },
			send_to_email: { value: send_to_email.val(), default: 1 },
		};

		const form_data = new FormData();
		form_data.append('action', 'atbdp_send_announcement');
		for (const field in fields_elm) {
			form_data.append(field, fields_elm[field].value);
		}

		announcement_is_sending = true;
		jQuery.ajax({
			type: 'post',
			url:  directorist_admin.ajaxurl,
			data: form_data,
			processData: false,
			contentType: false,
			beforeSend() {
				form_feedback.find('.announcement-feedback').html(
					'<div class="form-alert">Sending the announcement, please wait..</div>'
				);
			},
			success(response) {
				announcement_is_sending = false;
				if (response.message) {
					form_feedback.find('.announcement-feedback').html(
						`<div class="form-alert">${response.message}</div>`
					);
				}
			},
			error(error) {
				console.log({ error });
				announcement_is_sending = false;
			},
		});
	});

	// ─── Custom Tab Support ───────────────────────────────────────────────────────
	$('.atbds_wrapper a.nav-link').on('click', function (e) {
		e.preventDefault();
		const atbds_tabParent = $(this).parent().parent().find('a.nav-link');
		const $href = $(this).attr('href');
		$(atbds_tabParent).removeClass('active');
		$(this).addClass('active');

		switch ($(this).data('tabarea')) {
			case 'atbds_system-status-tab':
				$(`.tab-content[data-tabarea='atbds_system-status-tab'] >.tab-pane`).removeClass('active show');
				$(`.tab-content[data-tabarea='atbds_system-status-tab'] ${$href}`).addClass('active show');
				break;
			case 'atbds_system-info-tab':
				$(`.tab-content[data-tabarea='atbds_system-info-tab'] >.tab-pane`).removeClass('active show');
				$(`.tab-content[data-tabarea='atbds_system-info-tab'] ${$href}`).addClass('active show');
				break;
		}
	});

	// ─── Custom Tooltip ───────────────────────────────────────────────────────────
	$('.atbds_tooltip').on('hover', function () {
		const toolTipLabel = $(this).data('label');
		$(this).find('.atbds_tooltip__text').text(toolTipLabel).addClass('show');
	});
	$('.atbds_tooltip').on('mouseleave', function () {
		$('.atbds_tooltip__text').removeClass('show');
	});

	// ─── Screen detection ─────────────────────────────────────────────────────────
	const directory_type = $('select[name="directory_type"]').val();
	const isEditListingScreen =
		$('body').hasClass('post-php') &&
		$('body').hasClass('post-type-at_biz_dir');
	const hasRenderedListingFields =
		$('#directiost-listing-fields_wrapper .directorist-listing-fields')
			.children().length > 0;
	const editorLifecycleDebugEnabled = false;

	function editorLifecycleLog() {
		if (!editorLifecycleDebugEnabled) { return; }
		const args = Array.prototype.slice.call(arguments);
		args.unshift('[Directorist][Listing Editor]');
		console.log.apply(console, args);
	}

	function logEditorSnapshot(stage, $container, meta) {
		if (!editorLifecycleDebugEnabled) { return; }

		const container = $container && $container.length ? $container : $(document.body);
		const editorIds = getEditorIdsInContainer(container);
		const tinyMCE = (typeof window.tinymce !== 'undefined' && window.tinymce.editors)
			? window.tinymce.editors
			: [];
		const tinyMceEditorIds = (tinyMCE || []).map(function (ed) {
			return ed && ed.id ? ed.id : null;
		}).filter(Boolean);
		const qtagsInstances = (
			typeof window.QTags !== 'undefined' &&
			window.QTags &&
			window.QTags.instances
		) ? window.QTags.instances : {};
		const qtagsInstanceIds = Object.keys(qtagsInstances).filter(function (id) {
			return !!qtagsInstances[id];
		});
		const preInit = window.tinyMCEPreInit || {};

		editorLifecycleLog(
			'editor-snapshot',
			stage,
			Object.assign({
				selectedDirectoryType: $('select[name="directory_type"]').val() || null,
				editorIds: editorIds,
				textareaCount: container.find('textarea.wp-editor-area[id]').length,
				wpEditorWrapCount: container.find('.wp-editor-wrap').length,
				tinyMceContainerCount: container.find('.mce-tinymce').length,
				tinyMceIframeCount: container.find('iframe[id$="_ifr"]').length,
				quicktagsToolbarCount: container.find('.quicktags-toolbar,[id^="qt_"][id$="_toolbar"]').length,
				tinyMceEditorIds: tinyMceEditorIds,
				qtagsButtonsInitDone: (
					typeof window.QTags !== 'undefined' &&
					window.QTags
				) ? window.QTags.buttonsInitDone === true : null,
				qtagsInstanceIds: qtagsInstanceIds,
				mcePreinitKeys: Object.keys(preInit.mceInit || {}),
				qtPreinitKeys: Object.keys(preInit.qtInit || {}),
			}, meta || {})
		);
	}

	function ensureQuicktagsForEditor(editorId, qtInit, $context) {
		if (!editorId || typeof editorId !== 'string') { return false; }

		const $scope = $context && $context.length ? $context : $(document.body);
		const $textarea = $scope.find('#' + editorId);
		if (!$textarea.length) {
			editorLifecycleLog('quicktags-skip', editorId, 'textarea-missing');
			return false;
		}

		const toolbarSelector = '#qt_' + editorId + '_toolbar';
		const hasToolbar = $scope.find(toolbarSelector).length > 0 || $(toolbarSelector).length > 0;
		const hasInstance = (
			typeof window.QTags !== 'undefined' &&
			window.QTags &&
			window.QTags.instances &&
			window.QTags.instances[editorId]
		);

		if (hasToolbar && hasInstance) {
			editorLifecycleLog('quicktags-ready', editorId);
			return true;
		}

		if (typeof window.quicktags !== 'function') {
			editorLifecycleLog('quicktags-skip', editorId, 'quicktags-api-missing');
			return false;
		}

		if (hasInstance && typeof window.QTags.instances[editorId].remove === 'function') {
			try { window.QTags.instances[editorId].remove(); } catch (e) { /* ignore */ }
		}

		const quicktagsSettings = Object.assign(
			{},
			(qtInit && typeof qtInit === 'object') ? qtInit : {},
			{ id: editorId }
		);

		try {
			window.quicktags(quicktagsSettings);
			editorLifecycleLog('quicktags-reinit', editorId);
		} catch (e) {
			editorLifecycleLog('quicktags-reinit-fail', editorId, e && e.message ? e.message : e);
		}

		if (
			typeof window.QTags !== 'undefined' &&
			window.QTags &&
			typeof window.QTags._buttonsInit === 'function'
		) {
			try {
				window.QTags._buttonsInit(editorId);
			} catch (e) { /* ignore */ }
		}

		const hasToolbarAfterInit = $(toolbarSelector).length > 0;
		editorLifecycleLog(
			'quicktags-verify',
			editorId,
			hasToolbarAfterInit ? 'toolbar-ready' : 'toolbar-missing'
		);

		return hasToolbarAfterInit;
	}

	function getEditorIdsInContainer($container) {
		if (!$container || !$container.length) { return []; }

		const idsMap = {};
		$container.find('textarea.wp-editor-area[id], .wp-editor-wrap textarea[id]').each(function () {
			const editorId = $(this).attr('id');
			if (editorId) { idsMap[editorId] = true; }
		});

		$container.find('.wp-editor-wrap[id$="-wrap"]').each(function () {
			const wrapId = $(this).attr('id');
			if (!wrapId) { return; }
			let editorId = '';
			const match = wrapId.match(/^wp-(.+)-wrap$/);
			if (match && match[1]) {
				// Native WP wrapper format: wp-{editorId}-wrap
				editorId = match[1];
			} else {
				editorId = wrapId.replace(/-wrap$/, '');
			}
			if (editorId) { idsMap[editorId] = true; }
		});

		return Object.keys(idsMap);
	}

	function unlockListingFormUi() {
		$('#listing_form_info').find('.directorist_loader').remove();
		$('select[name="directory_type"]')
			.parent('.inside')
			.find('.directorist_loader')
			.remove();
		$('select[name="directory_type"]')
			.closest('#poststuff')
			.find('#publishing-action')
			.removeClass('directorist_disable');
	}

	const directoryTypeReloadEventNames = [
		'directorist-search-form-nav-tab-reloaded',
		'directorist-reload-select2-fields',
		'directorist-reload-map-api-field',
		'triggerSlice',
		'directorist-reload-plupload',
		'directorist-type-change',
	];

	function dispatchDirectoryTypeReloadEvents(context) {
		const detail = context || {};

		directoryTypeReloadEventNames.forEach(function (eventName) {
			window.dispatchEvent(new CustomEvent(eventName, { detail: detail }));
			if (document && document.body) {
				document.body.dispatchEvent(new CustomEvent(eventName, { detail: detail }));
			}
		});
		editorLifecycleLog('dispatched-events', directoryTypeReloadEventNames);
	}

	// ─── WP Editor — destroy ──────────────────────────────────────────────────────
	/**
	 * Destroy all TinyMCE + Quicktags editor instances found inside $container.
	 *
	 * MUST be called synchronously BEFORE the container's innerHTML is wiped
	 * (.empty()) so TinyMCE can detach its iframes from live DOM nodes without
	 * throwing errors. After this call the container can be safely emptied.
	 *
	 * @param {jQuery} $container
	 */
	function destroyEditorsInContainer($container) {
		if (!$container || !$container.length) { return; }

		const editorIds = getEditorIdsInContainer($container);
		if (!editorIds.length) {
			editorLifecycleLog('destroy-skip', 'no-editors-found');
			return;
		}

		editorLifecycleLog('destroy-start', editorIds);
		editorIds.forEach(function (editorId) {
			const hasQTagsInstance = (
				typeof window.QTags !== 'undefined' &&
				window.QTags &&
				window.QTags.instances &&
				window.QTags.instances[editorId]
			);
			const isQTagsBooting = (
				typeof window.QTags !== 'undefined' &&
				window.QTags &&
				window.QTags.buttonsInitDone === false
			);

			if (hasQTagsInstance && isQTagsBooting) {
				editorLifecycleLog('destroy-defer', editorId, 'qtags-buttons-pending');
				return;
			}

			// 1. wp.editor.remove() — the correct WP public API (WP >= 4.8).
			//    This handles both TinyMCE and Quicktags in one call.
			if (
				typeof window.wp !== 'undefined' &&
				wp.editor &&
				typeof wp.editor.remove === 'function'
			) {
				try { wp.editor.remove(editorId); } catch (e) { /* ignore */ }
			}

			// 2. Belt-and-suspenders: direct TinyMCE teardown in case wp.editor.remove
			//    was not enough (e.g. the editor was initialised outside WP's API).
			if (typeof window.tinymce !== 'undefined') {
				const ed = tinymce.get(editorId);
				if (ed) {
					try { ed.save(); } catch (e) { /* ignore */ }
					try { tinymce.execCommand('mceRemoveEditor', false, editorId); } catch (e) { /* ignore */ }
					try { tinymce.remove('#' + editorId); } catch (e) { /* ignore */ }
				}
			}

			// 3. Quicktags cleanup in case wp.editor.remove missed it.
			if (
				typeof window.QTags !== 'undefined' &&
				window.QTags.instances &&
				window.QTags.instances[editorId]
			) {
				try {
					$('#qt_' + editorId + '_toolbar').remove();
					delete window.QTags.instances[editorId];
				} catch (e) { /* ignore */ }
			}
		});
	}

	// ─── WP Editor — reinit ───────────────────────────────────────────────────────
	/**
	 * Re-initialise every WP editor found inside $container after AJAX injection.
	 *
	 * WordPress's wp_editor() outputs an inline <script> that calls
	 * wp.editor.initialize(). jQuery's .append() runs those inline scripts
	 * automatically, so in most cases the editor self-initialises and this
	 * function is a no-op (tinymce.get(id) already returns the instance).
	 *
	 * The 100 ms delay gives jQuery's globalEval time to run first.
	 * If the inline script did not run (server strips scripts, or timing edge
	 * case), we fall back to calling wp.editor.initialize() ourselves using
	 * whatever settings are registered in tinyMCEPreInit.
	 *
	 * @param {jQuery} $container
	 */
	function reinitEditorsInContainer($container, attempt) {
		if (!$container || !$container.length) { return; }
		const retryAttempt = Number(attempt) || 0;
		const maxRetryAttempts = 8;

		setTimeout(function () {
			const editorIds = getEditorIdsInContainer($container);
			if (!editorIds.length) {
				editorLifecycleLog('reinit-skip', 'no-editors-found');
				return;
			}

			editorLifecycleLog('reinit-start', editorIds);
			let hasPendingEditorInit = false;
			editorIds.forEach(function (editorId) {
				const escapedEditorId = String(editorId).replace(/"/g, '\\"');
				const hasTextarea = $container.find(`textarea[id="${escapedEditorId}"]`).length > 0;
				if (!hasTextarea) {
					editorLifecycleLog('reinit-skip', editorId, 'textarea-not-found');
					return;
				}

				// Already initialised — nothing to do.
				if (typeof window.tinymce !== 'undefined') {
					const existingEditor = tinymce.get(editorId);
					if (existingEditor) {
						const editorContainer = existingEditor.getContainer
							? existingEditor.getContainer()
							: null;
						const hasLiveEditorContainer =
							editorContainer &&
							document.body &&
							document.body.contains(editorContainer);

						if (hasLiveEditorContainer) {
							if (existingEditor.initialized) {
								editorLifecycleLog('reinit-skip', editorId, 'already-live');
								return;
							}

							// Editor exists but is still booting; wait and retry instead of double-init.
							hasPendingEditorInit = true;
							editorLifecycleLog('reinit-wait', editorId, 'booting');
							return;
						}

						// Stale instance references detached markup after a DOM swap.
						try { existingEditor.remove(); } catch (e) { /* ignore */ }
					}
				}

				if (
					typeof window.wp === 'undefined' ||
					!wp.editor ||
					typeof wp.editor.initialize !== 'function'
				) {
					editorLifecycleLog('reinit-skip', editorId, 'wp-editor-unavailable');
					return;
				}

				const preinit = window.tinyMCEPreInit || {};
				const mceInit = (preinit.mceInit || {})[editorId];
				const qtInit  = (preinit.qtInit  || {})[editorId];

				// AJAX-injected wp_editor() output should register per-editor preinit.
				// If missing, wait briefly before falling back to defaults.
				if (!mceInit && retryAttempt < maxRetryAttempts) {
					hasPendingEditorInit = true;
					editorLifecycleLog('reinit-wait', editorId, 'preinit-missing');
					return;
				}

				const qtagsInstancePendingButtons = (
					typeof window.QTags !== 'undefined' &&
					window.QTags &&
					window.QTags.buttonsInitDone === false &&
					window.QTags.instances &&
					window.QTags.instances[editorId]
				);
				if (qtagsInstancePendingButtons && retryAttempt < maxRetryAttempts) {
					hasPendingEditorInit = true;
					editorLifecycleLog('reinit-wait', editorId, 'qtags-buttons-pending');
					return;
				}

				// Prefer per-editor preinit config. If missing, use WP defaults.
				const mceSettings = mceInit
					? Object.assign({}, mceInit, { selector: '#' + editorId })
					: true;

				wp.editor.initialize(editorId, {
					tinymce:      mceSettings,
					quicktags:    qtInit ? Object.assign({}, qtInit) : true,
					mediaButtons: true,
				});
				editorLifecycleLog('reinit-done', editorId, mceInit ? 'preinit' : 'wp-default');
				setTimeout(function () {
					ensureQuicktagsForEditor(editorId, qtInit, $container);
				}, 0);

				const wantsTinyMce = mceSettings !== false;
				if (wantsTinyMce) {
					const ensureTinyMceReady = function (verifyAttempt) {
						const tinyMceEditor = (
							typeof window.tinymce !== 'undefined' &&
							window.tinymce &&
							typeof window.tinymce.get === 'function'
						) ? window.tinymce.get(editorId) : null;
						const hasTinyMceEditor = !!(tinyMceEditor && tinyMceEditor.initialized);
						const hasTinyMceContainer = $container.find('#wp-' + editorId + '-wrap .mce-tinymce').length > 0;

						if (hasTinyMceEditor || hasTinyMceContainer) {
							editorLifecycleLog('reinit-verify', editorId, 'tinymce-ready', verifyAttempt);
							return;
						}

						if (verifyAttempt < maxRetryAttempts) {
							setTimeout(function () {
								ensureTinyMceReady(verifyAttempt + 1);
							}, 120);
							return;
						}

						editorLifecycleLog('reinit-verify', editorId, 'tinymce-missing-after-init');
					};

					setTimeout(function () {
						if (
							typeof window.switchEditors !== 'undefined' &&
							window.switchEditors &&
							typeof window.switchEditors.go === 'function'
						) {
							try {
								window.switchEditors.go(editorId, 'tmce');
								editorLifecycleLog('reinit-switch', editorId, 'tmce');
							} catch (e) {
								editorLifecycleLog('reinit-switch-fail', editorId, e && e.message ? e.message : e);
							}
						}

						ensureTinyMceReady(0);
					}, 0);
				}
			});

			if (hasPendingEditorInit && retryAttempt < maxRetryAttempts) {
				editorLifecycleLog('reinit-retry', retryAttempt + 1);
				reinitEditorsInContainer($container, retryAttempt + 1);
			}
		}, 100); // 100 ms: enough for jQuery globalEval; avoid 0 which races on slow machines
	}

	// ─── Race-condition guard ─────────────────────────────────────────────────────
	let ajaxRequestSeq = 0;
	let activeListingFormRequest = null;

	// ─── Directory type change handler ────────────────────────────────────────────
	const localized_data = directorist_admin.add_listing_data;

	$('body').on(
		'change',
		'select[name="directory_type"]',
		debounce(function () {
			$(this)
				.parent('.inside')
				.append('<span class="directorist_loader"></span>');

			admin_listing_form($(this).val(), false);

			$(this)
				.closest('#poststuff')
				.find('#publishing-action')
				.addClass('directorist_disable');

			if (!localized_data.is_admin) {
				[
					'#directorist-select-st-s-js', '#directorist-select-st-e-js',
					'#directorist-select-sn-s-js', '#directorist-select-sn-e-js',
					'#directorist-select-mn-s-js', '#directorist-select-mn-e-js',
					'#directorist-select-tu-s-js', '#directorist-select-tu-e-js',
					'#directorist-select-wd-s-js', '#directorist-select-wd-e-js',
					'#directorist-select-th-s-js', '#directorist-select-th-e-js',
					'#directorist-select-fr-s-js', '#directorist-select-fr-e-js',
				].forEach(function (sel) {
					if ($(sel).length) { pureScriptSelect(sel); }
				});
			}
		}, 270)
	);

	$(document)
		.off('click.directorist-switch-html', '.wp-switch-editor.switch-html')
		.on('click.directorist-switch-html', '.wp-switch-editor.switch-html', function () {
			const editorId = $(this).attr('data-wp-editor-id');
			if (!editorId || editorId.indexOf('directorist_html_') !== 0) { return; }

			setTimeout(function () {
				const preinit = window.tinyMCEPreInit || {};
				const qtInit = (preinit.qtInit || {})[editorId];
				ensureQuicktagsForEditor(editorId, qtInit);
			}, 0);
		});

	// ─── Custom field "see more" ──────────────────────────────────────────────────
	function customFieldSeeMore() {
		if (!$('.directorist-custom-field-btn-more').length) { return; }
		$('.directorist-custom-field-btn-more').each((index, element) => {
			const fieldWrapper = $(element).closest(
				'.directorist-custom-field-checkbox, .directorist-custom-field-radio'
			);
			const customField = fieldWrapper.find('.directorist-checkbox, .directorist-radio');
			$(customField).slice(20, customField.length).slideUp();
			if (customField.length <= 20) { $(element).slideUp(); }
		});
	}

	// ─── assetsNeedToWorkInVirtualDom ─────────────────────────────────────────────
	/**
	 * Initialise all dynamic UI behaviours that live inside the AJAX-replaced
	 * fields wrapper.
	 *
	 * Uses NAMESPACED delegated events (.off(ns).on(ns)) so calling this function
	 * multiple times (once per directory-type switch) is safe — old handlers are
	 * replaced, never stacked.
	 */
	function assetsNeedToWorkInVirtualDom() {

		// ── Price type toggles ────────────────────────────────────────────────
		function getPriceTypeInput(typeId) {
			return $(`#${$(`[for="${typeId}"]`).data('option')}`);
		}

		$(document)
			.off('change.directorist-price', '.directorist-form-pricing-field__options input')
			.on('change.directorist-price',  '.directorist-form-pricing-field__options input', function () {
				const $otherOptions = $(this)
					.parent()
					.siblings('.directorist-checkbox')
					.find('input');
				$otherOptions.prop('checked', false);
				getPriceTypeInput($otherOptions.attr('id')).hide();
				if (this.checked) {
					getPriceTypeInput(this.id).show();
				} else {
					getPriceTypeInput(this.id).hide();
				}
			});

		if ($('.directorist-form-pricing-field').hasClass('price-type-both')) {
			$('#price_range, #price').hide();
			const $selectedPriceType = $('.directorist-form-pricing-field__options input:checked');
			if ($selectedPriceType.length) {
				getPriceTypeInput($selectedPriceType.attr('id')).show();
			} else {
				$($('.directorist-form-pricing-field__options input').get(0))
					.prop('checked', true)
					.trigger('change.directorist-price');
			}
		}

		// ── Preview image uploader (delegated — survives DOM replacement) ─────
		$(document)
			.off('click.directorist-upload-header', '.upload-header')
			.on('click.directorist-upload-header',  '.upload-header', function (element) {
				element.preventDefault();

				const uploadModal = wp.media.frames.file_frame = wp.media({
					title:  directorist_admin.i18n_text.select_prv_img,
					button: { text: directorist_admin.i18n_text.insert_prv_img },
				});

				uploadModal.on('select', function () {
					prv_image   = uploadModal.state().get('selection').first().toJSON();
					prv_url     = prv_image.id;
					prv_img_url = prv_image.url;

					$('.listing_prv_img').val(prv_url);
					$('.change_listing_prv_img').attr('src', prv_img_url);
					$('.upload-header').html('Change Preview Image');
					$('.remove_prev_img').show();
				});

				uploadModal.open();
			});

		$(document)
			.off('click.directorist-remove-prev', '.remove_prev_img')
			.on('click.directorist-remove-prev',  '.remove_prev_img', function (e) {
				e.preventDefault();
				$(this).hide();
				$('.listing_prv_img').attr('value', '');
				$('.change_listing_prv_img').attr('src', '');
			});

		if ($('.change_listing_prv_img').attr('src') === '') {
			$('.remove_prev_img').hide();
		} else {
			$('.remove_prev_img').show();
		}

		// ── Manual coordinate toggle ──────────────────────────────────────────
		if (!$('input#manual_coordinate').is(':checked')) {
			$('.directorist-map-coordinates').hide();
		}
		$(document)
			.off('click.directorist-coord', '#manual_coordinate')
			.on('click.directorist-coord',  '#manual_coordinate', function () {
				if ($('input#manual_coordinate').is(':checked')) {
					$('.directorist-map-coordinates').show();
				} else {
					$('.directorist-map-coordinates').hide();
				}
			});
	}

	// ─── Core AJAX function ───────────────────────────────────────────────────────
	/**
	 * Load listing form fields for the given directory type via AJAX.
	 *
	 * @param {string}  directory_type  Slug/ID of the selected directory type.
	 * @param {boolean} [skipDomSwap]   Skip DOM replacement on edit-screen initial
	 *                                  load (fields already rendered by PHP) but
	 *                                  still run all post-AJAX initialisers.
	 */
	function admin_listing_form(directory_type, skipDomSwap) {
		const thisSeq        = ++ajaxRequestSeq;
		const $fieldsWrapper = $('#directiost-listing-fields_wrapper .directorist-listing-fields');
		logEditorSnapshot('ajax-start', $fieldsWrapper, {
			sequence: thisSeq,
			directory_type: directory_type,
			skipDomSwap: !!skipDomSwap,
		});

		if (
			activeListingFormRequest &&
			activeListingFormRequest.readyState !== 4
		) {
			activeListingFormRequest.abort();
			editorLifecycleLog('ajax-abort-previous', thisSeq);
		}

		editorLifecycleLog('ajax-start', {
			sequence: thisSeq,
			directory_type: directory_type,
			skipDomSwap: !!skipDomSwap,
		});

		// Destroy before empty() so TinyMCE can detach from live nodes
		if (!skipDomSwap) {
			destroyEditorsInContainer($fieldsWrapper);
			logEditorSnapshot('post-destroy', $fieldsWrapper, { sequence: thisSeq });
		}

		activeListingFormRequest = $.ajax({
			type: 'post',
			url:  directorist_admin.ajaxurl,
			data: {
				action:            'atbdp_dynamic_admin_listing_form',
				directory_type:    directory_type,
				listing_id:        $('#directiost-listing-fields_wrapper').data('id'),
				directorist_nonce: directorist_admin.directorist_nonce,
			},
			success(response) {
				// Discard stale response from a superseded request
				if (thisSeq !== ajaxRequestSeq) {
					editorLifecycleLog('ajax-stale-discarded', thisSeq);
					return;
				}

				if (response.error) {
					console.log({ response });
					unlockListingFormUi();
					return;
				}

				if (!skipDomSwap) {
					// Inject new fields HTML.
					// jQuery's .append() runs inline <script> tags automatically,
					// so WP's own wp.editor.initialize() call fires here.
					// reinitEditorsInContainer() is the safety net for when it doesn't.
					$fieldsWrapper
						.empty()
						.append(response.data['listing_meta_fields']);
					logEditorSnapshot('post-dom-append', $fieldsWrapper, { sequence: thisSeq });

					$('#at_biz_dir-locationchecklist')
						.empty()
						.html(response.data['listing_locations']);
					$('#at_biz_dir-categorychecklist')
						.empty()
						.html(response.data['listing_categories']);
					$('#at_biz_dir-categorychecklist-pop')
						.empty()
						.html(response.data['listing_pop_categories']);
					$('#at_biz_dir-locationchecklist-pop')
						.empty()
						.html(response.data['listing_pop_locations']);
					$('.misc-pub-atbdp-expiration-time')
						.empty()
						.html(response.data['listing_expiration']);

					// Re-inject required external scripts
					if (response.data['required_js_scripts']) {
						const scripts = response.data['required_js_scripts'];
						for (const script_id in scripts) {
							const old_script = document.getElementById(script_id);
							if (old_script) { old_script.remove(); }
							const script = document.createElement('script');
							script.id  = script_id;
							script.src = scripts[script_id];
							document.body.appendChild(script);
						}
					}

					// Safety-net editor reinit (100 ms after jQuery's globalEval)
					reinitEditorsInContainer($fieldsWrapper);
					setTimeout(function () {
						logEditorSnapshot('post-reinit-delay', $fieldsWrapper, { sequence: thisSeq });
					}, 450);
				}

				// Always run — regardless of skipDomSwap
				assetsNeedToWorkInVirtualDom();
				logEditorSnapshot('post-virtual-dom-init', $fieldsWrapper, { sequence: thisSeq });

				unlockListingFormUi();

				if ($('.directorist-color-field-js').length) {
					$('.directorist-color-field-js').wpColorPicker().empty();
				}

				dispatchDirectoryTypeReloadEvents({
					directory_type: directory_type,
					sequence: thisSeq,
					skip_dom_swap: !!skipDomSwap,
				});

				customFieldSeeMore();
			},
			error(jqXHR, textStatus, errorThrown) {
				if (textStatus === 'abort') {
					editorLifecycleLog('ajax-aborted', thisSeq);
					return;
				}

				console.log({
					error: jqXHR,
					textStatus: textStatus,
					errorThrown: errorThrown,
				});
				unlockListingFormUi();
			},
			complete() {
				if (thisSeq === ajaxRequestSeq) {
					editorLifecycleLog('ajax-complete', thisSeq);
				}
			},
		});
	}

	// ─── Default directory type button ────────────────────────────────────────────
	$('body').on('click', '.submitdefault', function (e) {
		e.preventDefault();
		$(this).children('.submitDefaultCheckbox').prop('checked', true);
		const defaultSubmitDom = $(this);
		defaultSubmitDom
			.closest('.directorist_listing-actions')
			.append('<span class="directorist_loader"></span>');
		$.ajax({
			type: 'post',
			url:  directorist_admin.ajaxurl,
			data: {
				action:  'atbdp_listing_default_type',
				type_id: $(this).data('type-id'),
				nonce:   directorist_admin.nonce,
			},
			success(response) {
				defaultSubmitDom
					.closest('.directorist_listing-actions')
					.siblings('.directorist_notifier')
					.append(`<span class="atbd-listing-type-active-status">${response}</span>`);
				defaultSubmitDom
					.closest('.directorist_listing-actions')
					.children('.directorist_loader')
					.remove();
				setTimeout(function () { location.reload(); }, 500);
			},
		});
	});

	// ─── Initial page load ────────────────────────────────────────────────────────
	// WHY window "load" and not DOMContentLoaded:
	//   DOMContentLoaded fires before WordPress's enqueued scripts (tinymce,
	//   quicktags, wp-editor) finish executing. Calling admin_listing_form() there
	//   means destroyEditorsInContainer() / reinitEditorsInContainer() run before
	//   those globals exist, so editor management silently fails.
	//   window "load" fires after ALL scripts are ready — safe to touch TinyMCE.
	//
	// WHY the change handler is on DOMContentLoaded (above):
	//   Binding event listeners does not require TinyMCE to be ready; only the
	//   AJAX callback does. Binding on DOMContentLoaded ensures we never miss a
	//   change that happens before window "load" fires.
	window.addEventListener('load', function () {
		if (!directory_type) { return; }

		// Edit screen with already-rendered PHP fields: skip DOM replacement,
		// but still run assetsNeedToWorkInVirtualDom() and other initialisers
		// that were previously never called.
		const skipDomSwap = isEditListingScreen && hasRenderedListingFields;
		admin_listing_form(directory_type, skipDomSwap);
	});
});
