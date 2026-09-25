const DEFAULT_FOCAL_POINT = { x: 50, y: 50 };

let modalIndex = 0;

function clamp( value ) {
	return Math.min( 100, Math.max( 0, Number( value ) || 0 ) );
}

function normalizeFocalPoint( focalPoint = DEFAULT_FOCAL_POINT ) {
	return {
		x: Math.round( clamp( focalPoint.x ) * 100 ) / 100,
		y: Math.round( clamp( focalPoint.y ) * 100 ) / 100,
	};
}

function isDefaultFocalPoint( focalPoint = DEFAULT_FOCAL_POINT ) {
	const normalizedPoint = normalizeFocalPoint( focalPoint );

	return (
		normalizedPoint.x === DEFAULT_FOCAL_POINT.x &&
		normalizedPoint.y === DEFAULT_FOCAL_POINT.y
	);
}

function isEnabled( value ) {
	return ! [ false, 0, '0', 'false' ].includes( value );
}

function getUploaderConfig( mediaUploader ) {
	const container = mediaUploader?.container?.[ 0 ];

	if ( ! container ) {
		return {};
	}

	try {
		return JSON.parse( container.getAttribute( 'data-uploader' ) || '{}' );
	} catch ( error ) {
		return {};
	}
}

function getMetaById( mediaUploader, id ) {
	return mediaUploader.filesMeta.find(
		( fileMeta ) => String( fileMeta.id ) === String( id )
	);
}

function createButton( className, label ) {
	const button = document.createElement( 'button' );
	button.type = 'button';
	button.className = className;
	button.textContent = label;

	return button;
}

function getText( dictionary, key, fallback ) {
	return dictionary?.[ key ] || fallback;
}

function normalizePreviewDisplay( display = {} ) {
	const allowedModes = [ 'cover', 'contain', 'full' ];
	const allowedSizeMethods = [ 'px', 'ratio' ];
	const allowedBackgrounds = [ 'blur', 'color' ];

	return {
		mode: allowedModes.includes( display.mode ) ? display.mode : 'cover',
		sizeBy: allowedSizeMethods.includes( display.size_by )
			? display.size_by
			: 'px',
		width: Math.max( 1, Number( display.width ) || 360 ),
		height: Math.max( 1, Number( display.height ) || 300 ),
		backgroundType: allowedBackgrounds.includes( display.background_type )
			? display.background_type
			: 'blur',
		backgroundColor: display.background_color || '#fff',
	};
}

function getModeHelp( dictionary, mode ) {
	const help = {
		contain: getText(
			dictionary,
			'image_focus_help_contain',
			'The complete image remains visible. Focus controls its alignment and is also used on cropped thumbnail surfaces.'
		),
		cover: getText(
			dictionary,
			'image_focus_help_cover',
			'Focus controls which part of the Preview image remains visible when the card crops it.'
		),
		full: getText(
			dictionary,
			'image_focus_help_full',
			'The Original archive image is uncropped. Focus is saved for maps, dashboard thumbnails, and other cropped surfaces.'
		),
	};

	return help[ mode ] || help.cover;
}

function createFocalPointToolbar( dictionary, display ) {
	const toolbar = document.createElement( 'div' );
	toolbar.className = 'directorist-image-focus-toolbar';
	toolbar.hidden = true;
	toolbar.setAttribute(
		'aria-label',
		getText( dictionary, 'image_focus_status_label', 'Image focus' )
	);

	const summary = document.createElement( 'div' );
	summary.className = 'directorist-image-focus-toolbar__summary';

	const heading = document.createElement( 'div' );
	heading.className = 'directorist-image-focus-toolbar__heading';

	const label = document.createElement( 'strong' );
	label.className = 'directorist-image-focus-toolbar__label';
	label.textContent = getText(
		dictionary,
		'image_focus_status_label',
		'Image focus'
	);

	const status = document.createElement( 'span' );
	status.className = 'directorist-image-focus-toolbar__status';
	status.setAttribute( 'aria-live', 'polite' );

	const description = document.createElement( 'p' );
	description.className = 'directorist-image-focus-toolbar__description';
	description.textContent = getModeHelp( dictionary, display.mode );

	heading.append( label, status );
	summary.append( heading, description );

	const actions = document.createElement( 'div' );
	actions.className = 'directorist-image-focus-toolbar__actions';

	const editButton = createButton(
		'directorist-btn directorist-btn-sm directorist-btn-primary directorist-image-focus-toolbar__edit',
		getText( dictionary, 'set_image_focus', 'Set image focus' )
	);
	const resetButton = createButton(
		'directorist-btn directorist-btn-sm directorist-btn-outline-primary directorist-image-focus-toolbar__reset',
		getText( dictionary, 'image_focus_reset', 'Reset to center' )
	);

	actions.append( editButton, resetButton );
	toolbar.append( summary, actions );

	return {
		editButton,
		resetButton,
		status,
		toolbar,
	};
}

function createFocalPointModal( dictionary, display ) {
	modalIndex += 1;

	const titleId = `directorist-image-focus-title-${ modalIndex }`;
	const modal = document.createElement( 'div' );
	modal.className = 'directorist-image-focus-modal';
	modal.hidden = true;
	modal.setAttribute( 'role', 'dialog' );
	modal.setAttribute( 'aria-modal', 'true' );
	modal.setAttribute( 'aria-labelledby', titleId );

	const backdrop = document.createElement( 'div' );
	backdrop.className = 'directorist-image-focus-modal__backdrop';

	const dialog = document.createElement( 'div' );
	dialog.className = 'directorist-image-focus-modal__dialog';

	const header = document.createElement( 'div' );
	header.className = 'directorist-image-focus-modal__header';

	const headingWrap = document.createElement( 'div' );
	const title = document.createElement( 'h2' );
	title.id = titleId;
	title.className = 'directorist-image-focus-modal__title';
	title.textContent = getText(
		dictionary,
		'image_focus_title',
		'Image focal point'
	);

	const description = document.createElement( 'p' );
	description.className = 'directorist-image-focus-modal__description';
	description.textContent = getModeHelp( dictionary, display.mode );

	headingWrap.append( title, description );

	const closeButton = createButton(
		'directorist-image-focus-modal__close',
		'×'
	);
	closeButton.setAttribute(
		'aria-label',
		getText( dictionary, 'image_focus_close', 'Close focal point editor' )
	);

	header.append( headingWrap, closeButton );

	const body = document.createElement( 'div' );
	body.className = 'directorist-image-focus-modal__body';

	const editorColumn = document.createElement( 'div' );
	editorColumn.className = 'directorist-image-focus-modal__editor';

	const stage = createButton( 'directorist-image-focus-stage', '' );
	stage.setAttribute(
		'aria-label',
		getText(
			dictionary,
			'image_focus_move',
			'Move the focal point. Use the arrow keys for precise adjustment.'
		)
	);

	const stageImage = document.createElement( 'img' );
	stageImage.className = 'directorist-image-focus-stage__image';
	stageImage.alt = '';

	const marker = document.createElement( 'span' );
	marker.className = 'directorist-image-focus-stage__marker';
	marker.setAttribute( 'aria-hidden', 'true' );
	stage.append( stageImage, marker );

	const controls = document.createElement( 'div' );
	controls.className = 'directorist-image-focus-controls';

	const xLabel = document.createElement( 'label' );
	xLabel.className = 'directorist-image-focus-controls__label';
	const xLabelText = document.createElement( 'span' );
	xLabelText.textContent = getText(
		dictionary,
		'image_focus_horizontal',
		'Horizontal position'
	);
	const xValue = document.createElement( 'output' );
	const xRange = document.createElement( 'input' );
	xRange.type = 'range';
	xRange.min = '0';
	xRange.max = '100';
	xRange.step = '1';
	xRange.className = 'directorist-image-focus-controls__range';
	xLabel.append( xLabelText, xValue, xRange );

	const yLabel = document.createElement( 'label' );
	yLabel.className = 'directorist-image-focus-controls__label';
	const yLabelText = document.createElement( 'span' );
	yLabelText.textContent = getText(
		dictionary,
		'image_focus_vertical',
		'Vertical position'
	);
	const yValue = document.createElement( 'output' );
	const yRange = document.createElement( 'input' );
	yRange.type = 'range';
	yRange.min = '0';
	yRange.max = '100';
	yRange.step = '1';
	yRange.className = 'directorist-image-focus-controls__range';
	yLabel.append( yLabelText, yValue, yRange );

	controls.append( xLabel, yLabel );
	editorColumn.append( stage, controls );

	const previewColumn = document.createElement( 'div' );
	previewColumn.className = 'directorist-image-focus-modal__preview-column';

	const previewTitle = document.createElement( 'h3' );
	previewTitle.className = 'directorist-image-focus-modal__preview-title';
	previewTitle.textContent = getText(
		dictionary,
		'image_focus_preview',
		'Listing card preview'
	);

	const preview = document.createElement( 'div' );
	preview.className = `directorist-image-focus-preview directorist-image-focus-preview--${ display.mode }`;

	if ( display.mode !== 'full' ) {
		preview.style.aspectRatio = `${ display.width } / ${ display.height }`;
	}

	if ( display.mode === 'contain' && display.backgroundType === 'color' ) {
		preview.style.backgroundColor = display.backgroundColor;
	}

	const previewBackground = document.createElement( 'img' );
	previewBackground.className = 'directorist-image-focus-preview__background';
	previewBackground.alt = '';

	const previewImage = document.createElement( 'img' );
	previewImage.className = 'directorist-image-focus-preview__image';
	previewImage.alt = '';

	if ( display.mode === 'contain' && display.backgroundType === 'blur' ) {
		preview.appendChild( previewBackground );
	}

	preview.appendChild( previewImage );

	const presets = document.createElement( 'div' );
	presets.className = 'directorist-image-focus-presets';

	[
		{
			label: getText( dictionary, 'image_focus_top', 'Top' ),
			x: 50,
			y: 0,
		},
		{
			label: getText( dictionary, 'image_focus_center', 'Center' ),
			x: 50,
			y: 50,
		},
		{
			label: getText( dictionary, 'image_focus_bottom', 'Bottom' ),
			x: 50,
			y: 100,
		},
	].forEach( ( preset ) => {
		const button = createButton(
			'directorist-image-focus-presets__button',
			preset.label
		);
		button.dataset.x = preset.x;
		button.dataset.y = preset.y;
		presets.appendChild( button );
	} );

	const resetButton = createButton(
		'directorist-image-focus-modal__reset',
		getText( dictionary, 'image_focus_reset', 'Reset to center' )
	);

	previewColumn.append( previewTitle, preview, presets, resetButton );
	body.append( editorColumn, previewColumn );

	const footer = document.createElement( 'div' );
	footer.className = 'directorist-image-focus-modal__footer';
	const cancelButton = createButton(
		'directorist-btn directorist-btn-sm directorist-btn-outline-primary',
		getText( dictionary, 'image_focus_cancel', 'Cancel' )
	);
	const doneButton = createButton(
		'directorist-btn directorist-btn-sm directorist-btn-primary',
		getText( dictionary, 'image_focus_done', 'Done' )
	);
	footer.append( cancelButton, doneButton );

	dialog.append( header, body, footer );
	modal.append( backdrop, dialog );
	document.body.appendChild( modal );

	return {
		backdrop,
		cancelButton,
		closeButton,
		doneButton,
		marker,
		modal,
		previewBackground,
		previewImage,
		presets,
		resetButton,
		stage,
		stageImage,
		xRange,
		xValue,
		yRange,
		yValue,
	};
}

/**
 * Add the focal-point editor to an initialized EZ Media Uploader instance.
 *
 * @param {Object} mediaUploader EZ Media Uploader instance.
 * @param {Object} dictionary    Localized UI strings.
 * @return {void}
 */
export function initImageFocalPointPicker( mediaUploader, dictionary = {} ) {
	const config = getUploaderConfig( mediaUploader );

	if (
		! Object.prototype.hasOwnProperty.call(
			config,
			'enable_image_focus'
		) ||
		! isEnabled( config.enable_image_focus ) ||
		! mediaUploader?.container?.[ 0 ]
	) {
		return;
	}

	const savedFocalPoints = config.image_focal_points || {};
	const display = normalizePreviewDisplay( config.focus_preview_display );

	mediaUploader.filesMeta.forEach( ( fileMeta ) => {
		if (
			fileMeta.attachmentID &&
			savedFocalPoints[ fileMeta.attachmentID ]
		) {
			const savedFocalPoint = normalizeFocalPoint(
				savedFocalPoints[ fileMeta.attachmentID ]
			);

			if ( ! isDefaultFocalPoint( savedFocalPoint ) ) {
				fileMeta.directoristFocalPoint = savedFocalPoint;
			}
		}
	} );

	mediaUploader.directoristImageFocusEnabled = true;

	const ui = createFocalPointModal( dictionary, display );
	const toolbarUi = createFocalPointToolbar( dictionary, display );
	let activeMeta = null;
	let activeTrigger = null;
	let previewMeta = null;
	let workingPoint = { ...DEFAULT_FOCAL_POINT };
	let isDragging = false;

	mediaUploader.container?.[ 0 ]?.insertAdjacentElement(
		'afterend',
		toolbarUi.toolbar
	);

	function getFocalPoint( fileMeta ) {
		return normalizeFocalPoint(
			fileMeta?.directoristFocalPoint || DEFAULT_FOCAL_POINT
		);
	}

	function setFocalPoint( fileMeta, focalPoint ) {
		if ( ! fileMeta ) {
			return;
		}

		const normalizedPoint = normalizeFocalPoint( focalPoint );

		if ( isDefaultFocalPoint( normalizedPoint ) ) {
			delete fileMeta.directoristFocalPoint;
			return;
		}

		fileMeta.directoristFocalPoint = normalizedPoint;
	}

	function renderWorkingPoint() {
		workingPoint = normalizeFocalPoint( workingPoint );
		ui.marker.style.left = `${ workingPoint.x }%`;
		ui.marker.style.top = `${ workingPoint.y }%`;
		ui.previewImage.style.objectPosition = `${ workingPoint.x }% ${ workingPoint.y }%`;
		ui.previewBackground.style.objectPosition = `${ workingPoint.x }% ${ workingPoint.y }%`;
		ui.xRange.value = workingPoint.x;
		ui.yRange.value = workingPoint.y;
		ui.xValue.textContent = `${ workingPoint.x }%`;
		ui.yValue.textContent = `${ workingPoint.y }%`;
	}

	function updatePointFromPointer( event ) {
		const bounds = ui.stage.getBoundingClientRect();

		if ( ! bounds.width || ! bounds.height ) {
			return;
		}

		workingPoint = {
			x: ( ( event.clientX - bounds.left ) / bounds.width ) * 100,
			y: ( ( event.clientY - bounds.top ) / bounds.height ) * 100,
		};
		renderWorkingPoint();
	}

	function updateThumbnailPreview( fileMeta, focalPoint ) {
		const featuredItem = mediaUploader.container[ 0 ]
			.querySelector( '.ezmu__featured_tag' )
			?.closest( '.ezmu__thumbnail-list-item' );

		if ( ! featuredItem || ! fileMeta ) {
			return;
		}

		const itemMeta = getMetaById( mediaUploader, featuredItem.dataset.id );

		if ( itemMeta !== fileMeta ) {
			return;
		}

		const thumbnail = featuredItem.querySelector( '.ezmu__thumbnail-img' );

		if ( thumbnail ) {
			const normalizedPoint = normalizeFocalPoint( focalPoint );
			thumbnail.style.objectPosition = `${ normalizedPoint.x }% ${ normalizedPoint.y }%`;
		}
	}

	function updateToolbar( fileMeta ) {
		previewMeta = fileMeta || null;
		toolbarUi.toolbar.hidden = ! previewMeta;

		if ( ! previewMeta ) {
			return;
		}

		const focalPoint = getFocalPoint( previewMeta );
		const hasCustomFocus = ! isDefaultFocalPoint( focalPoint );

		toolbarUi.status.textContent = getText(
			dictionary,
			hasCustomFocus
				? 'image_focus_custom_status'
				: 'image_focus_default_status',
			hasCustomFocus ? 'Custom' : 'Center (default)'
		);
		toolbarUi.status.classList.toggle(
			'directorist-image-focus-toolbar__status--custom',
			hasCustomFocus
		);
		toolbarUi.editButton.textContent = getText(
			dictionary,
			hasCustomFocus ? 'edit_image_focus' : 'set_image_focus',
			hasCustomFocus ? 'Edit focus' : 'Set image focus'
		);
		toolbarUi.resetButton.disabled = ! hasCustomFocus;
		updateThumbnailPreview( previewMeta, focalPoint );
	}

	function closeModal( commit = false ) {
		if ( commit && activeMeta ) {
			setFocalPoint( activeMeta, workingPoint );
			updateToolbar( activeMeta );
		}

		ui.modal.hidden = true;
		document.body.classList.remove( 'directorist-image-focus-modal-open' );

		if ( activeTrigger ) {
			activeTrigger.focus();
		}

		activeMeta = null;
		activeTrigger = null;
	}

	function openModal( fileMeta, trigger ) {
		const thumbnailItem = Array.from(
			mediaUploader.container[ 0 ].querySelectorAll(
				'.ezmu__thumbnail-list-item'
			)
		).find(
			( item ) =>
				getMetaById( mediaUploader, item.dataset.id ) === fileMeta
		);
		const renderedThumbnail = thumbnailItem?.querySelector(
			'.ezmu__thumbnail-img'
		);
		const imageSource =
			fileMeta.blob ||
			fileMeta.url ||
			renderedThumbnail?.currentSrc ||
			renderedThumbnail?.src;

		if ( ! imageSource ) {
			return;
		}

		activeMeta = fileMeta;
		activeTrigger = trigger;
		workingPoint = getFocalPoint( fileMeta );
		ui.stageImage.onload = () => {
			if ( ui.stageImage.naturalWidth && ui.stageImage.naturalHeight ) {
				ui.stage.style.aspectRatio = `${ ui.stageImage.naturalWidth } / ${ ui.stageImage.naturalHeight }`;
			}
		};
		ui.stageImage.src = imageSource;
		ui.previewImage.src = imageSource;
		ui.previewBackground.src = imageSource;

		if ( ui.stageImage.complete ) {
			ui.stageImage.onload();
		}

		renderWorkingPoint();
		ui.modal.hidden = false;
		document.body.classList.add( 'directorist-image-focus-modal-open' );
		ui.closeButton.focus();
	}

	function decorateFeaturedImage() {
		const featuredTag = mediaUploader.container[ 0 ].querySelector(
			'.ezmu__featured_tag'
		);

		if ( ! featuredTag ) {
			updateToolbar( null );
			return;
		}

		const item = featuredTag.closest( '.ezmu__thumbnail-list-item' );
		const fileMeta = item
			? getMetaById( mediaUploader, item.dataset.id )
			: null;

		if ( ! fileMeta ) {
			updateToolbar( null );
			return;
		}

		updateToolbar( fileMeta );
	}

	toolbarUi.editButton.addEventListener( 'click', () => {
		if ( previewMeta ) {
			openModal( previewMeta, toolbarUi.editButton );
		}
	} );

	toolbarUi.resetButton.addEventListener( 'click', () => {
		if ( ! previewMeta ) {
			return;
		}

		setFocalPoint( previewMeta, DEFAULT_FOCAL_POINT );
		updateToolbar( previewMeta );
	} );

	ui.stage.addEventListener( 'pointerdown', ( event ) => {
		isDragging = true;
		ui.stage.setPointerCapture?.( event.pointerId );
		updatePointFromPointer( event );
	} );

	ui.stage.addEventListener( 'pointermove', ( event ) => {
		if ( isDragging ) {
			updatePointFromPointer( event );
		}
	} );

	[ 'pointerup', 'pointercancel' ].forEach( ( eventName ) => {
		ui.stage.addEventListener( eventName, () => {
			isDragging = false;
		} );
	} );

	ui.stage.addEventListener( 'keydown', ( event ) => {
		const step = event.shiftKey ? 5 : 1;
		const keyChanges = {
			ArrowDown: { x: 0, y: step },
			ArrowLeft: { x: -step, y: 0 },
			ArrowRight: { x: step, y: 0 },
			ArrowUp: { x: 0, y: -step },
		};

		if ( ! keyChanges[ event.key ] ) {
			return;
		}

		event.preventDefault();
		workingPoint.x += keyChanges[ event.key ].x;
		workingPoint.y += keyChanges[ event.key ].y;
		renderWorkingPoint();
	} );

	ui.xRange.addEventListener( 'input', () => {
		workingPoint.x = ui.xRange.value;
		renderWorkingPoint();
	} );

	ui.yRange.addEventListener( 'input', () => {
		workingPoint.y = ui.yRange.value;
		renderWorkingPoint();
	} );

	ui.presets.addEventListener( 'click', ( event ) => {
		const button = event.target.closest( 'button[data-x][data-y]' );

		if ( ! button ) {
			return;
		}

		workingPoint = { x: button.dataset.x, y: button.dataset.y };
		renderWorkingPoint();
	} );

	ui.resetButton.addEventListener( 'click', () => {
		workingPoint = { ...DEFAULT_FOCAL_POINT };
		renderWorkingPoint();
	} );

	ui.doneButton.addEventListener( 'click', () => closeModal( true ) );
	ui.cancelButton.addEventListener( 'click', () => closeModal() );
	ui.closeButton.addEventListener( 'click', () => closeModal() );
	ui.backdrop.addEventListener( 'click', () => closeModal() );

	ui.modal.addEventListener( 'keydown', ( event ) => {
		if ( event.key === 'Escape' ) {
			event.preventDefault();
			closeModal();
			return;
		}

		if ( event.key !== 'Tab' ) {
			return;
		}

		const focusable = Array.from(
			ui.modal.querySelectorAll(
				'button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])'
			)
		).filter( ( element ) => ! element.hidden );

		if ( ! focusable.length ) {
			return;
		}

		const first = focusable[ 0 ];
		const last = focusable[ focusable.length - 1 ];

		if (
			event.shiftKey &&
			ui.modal.ownerDocument.activeElement === first
		) {
			event.preventDefault();
			last.focus();
		} else if (
			! event.shiftKey &&
			ui.modal.ownerDocument.activeElement === last
		) {
			event.preventDefault();
			first.focus();
		}
	} );

	const observer = new window.MutationObserver( decorateFeaturedImage );
	observer.observe( mediaUploader.thumbnailArea, {
		childList: true,
		subtree: true,
	} );

	decorateFeaturedImage();
}

/**
 * Focus the image controls when a listing owner follows the dashboard shortcut.
 *
 * @return {void}
 */
export function initImageFocalPointDeepLink() {
	const params = new window.URLSearchParams( window.location.search );

	if ( params.get( 'directorist_edit_focus' ) !== '1' ) {
		return;
	}

	const imageField = document.querySelector(
		'#directorist-add-listing-form .directorist-form-image-upload-field'
	);

	if ( ! imageField ) {
		return;
	}

	window.setTimeout( () => {
		imageField.scrollIntoView( { behavior: 'smooth', block: 'center' } );
		imageField.classList.add( 'directorist-image-focus-target' );

		const editButton = imageField.querySelector(
			'.directorist-image-focus-toolbar__edit:not([hidden])'
		);

		editButton?.focus( { preventScroll: true } );

		window.setTimeout( () => {
			imageField.classList.remove( 'directorist-image-focus-target' );
		}, 1800 );
	}, 350 );
}

/**
 * Append the selected preview image and its focal point to listing form data.
 *
 * @param {FormData} formData       Listing form data.
 * @param {Object}   uploaderEntry  Directorist uploader entry.
 * @param {Array}    uploadedImages Newly uploaded temporary images.
 * @return {void}
 */
export function appendImageFocalPointData(
	formData,
	uploaderEntry,
	uploadedImages = []
) {
	const mediaUploader = uploaderEntry?.media_uploader;

	if (
		uploaderEntry?.uploaders_data?.meta_name !== 'listing_img' ||
		! mediaUploader?.directoristImageFocusEnabled ||
		! mediaUploader.filesMeta?.length
	) {
		return;
	}

	const previewMeta = mediaUploader.filesMeta[ 0 ];
	const focalPoint = normalizeFocalPoint(
		previewMeta.directoristFocalPoint || DEFAULT_FOCAL_POINT
	);
	let previewSource = '';

	if ( previewMeta.attachmentID ) {
		previewSource = `attachment:${ previewMeta.attachmentID }`;
	} else if ( previewMeta.file ) {
		const uploadedPreview = uploadedImages.find(
			( image ) => image.file === previewMeta.file
		);

		if ( uploadedPreview?.uploadedFile ) {
			previewSource = `temp:${ uploadedPreview.uploadedFile }`;
		}
	}

	formData.append( 'listing_image_focal_x', focalPoint.x );
	formData.append( 'listing_image_focal_y', focalPoint.y );
	formData.append(
		'listing_image_focal_is_custom',
		isDefaultFocalPoint( focalPoint ) ? '0' : '1'
	);

	if ( previewSource ) {
		formData.append( 'listing_image_preview_source', previewSource );
	}
}
