/**
 * File upload handlers: plupload, ez-media-uploader, thumb removal.
 * Uses MutationObserver on document.body to detect uploads/deletes.
 * @param {jQuery} $
 * @param {Function} triggerFn - (fieldName, fieldKey, $changedField) => void
 */
export function setupFileUploadHandlers($, triggerFn) {
	const fileUploadObserver = new MutationObserver(function (mutations) {
		mutations.forEach(function (mutation) {
			if (
				mutation.type === 'attributes' &&
				mutation.attributeName === 'class'
			) {
				const $target = $(mutation.target);
				if (
					$target.hasClass('ezmu__preview-section') &&
					$target.hasClass('ezmu--show')
				) {
					const $imageWrapper = $target.closest(
						'.directorist-form-image-upload-field'
					);
					if ($imageWrapper.length) {
						setTimeout(function () {
							triggerFn(
								'listing_img',
								'listing_img',
								$imageWrapper.find('.ez-media-uploader').first()
							);
						}, 200);
					}
				}
				if (
					$target.hasClass('ezmu__preview-section') &&
					!$target.hasClass('ezmu--show')
				) {
					const $imageWrapper = $target.closest(
						'.directorist-form-image-upload-field'
					);
					if ($imageWrapper.length) {
						setTimeout(function () {
							triggerFn(
								'listing_img',
								'listing_img',
								$imageWrapper.find('.ez-media-uploader').first()
							);
						}, 200);
					}
				}
			}

			if (mutation.addedNodes.length > 0) {
				mutation.addedNodes.forEach(function (node) {
					if (node.nodeType !== 1) return;
					const $node = $(node);

					if (
						$node.hasClass('thumb') ||
						$node.closest('.plupload-thumbs').length ||
						$node.find('.thumb').length
					) {
						const $fileWrapper = $node.closest(
							'.directorist-form-group, .directorist-custom-field-file-upload'
						);
						if ($fileWrapper.length) {
							const fieldKey = resolveFileFieldKey($fileWrapper);
							if (fieldKey) {
								setTimeout(function () {
									triggerFn(
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

					if (
						$node.hasClass('ezmu__preview-section') ||
						$node.hasClass('ezmu--show') ||
						$node.closest('.ezmu__preview-section.ezmu--show')
							.length
					) {
						const $imageWrapper = $node.closest(
							'.directorist-form-image-upload-field'
						);
						if ($imageWrapper.length) {
							setTimeout(function () {
								triggerFn(
									'listing_img',
									'listing_img',
									$imageWrapper
										.find('.ez-media-uploader')
										.first()
								);
							}, 200);
						}
					}
				});
			}

			if (mutation.removedNodes.length > 0) {
				mutation.removedNodes.forEach(function (node) {
					if (node.nodeType !== 1) return;
					const $node = $(node);

					if (
						$node.hasClass('thumb') ||
						$node.closest('.plupload-thumbs').length ||
						$node.find('.thumb').length
					) {
						const $thumbsContainer = $(mutation.target);
						if (
							$thumbsContainer.hasClass('plupload-thumbs') ||
							$thumbsContainer.find('.plupload-thumbs').length
						) {
							const $fileWrapper = $thumbsContainer.closest(
								'.directorist-form-group, .directorist-custom-field-file-upload'
							);
							if ($fileWrapper.length) {
								const fieldKey =
									resolveFileFieldKey($fileWrapper);
								if (fieldKey) {
									setTimeout(function () {
										triggerFn(
											fieldKey,
											fieldKey,
											$fileWrapper
												.find('input[type="hidden"]')
												.first()
										);
									}, 300);
								}
							}
						}
					}

					if (
						$node.hasClass('ezmu__file-item') ||
						$node.hasClass('ezmu__new-file') ||
						$node.closest('.ez-media-uploader').length ||
						$node.hasClass('ezmu__old-files-meta') ||
						$node.find('.ezmu__file-item, .ezmu__new-file').length
					) {
						const $uploaderContainer = $(mutation.target);
						if (
							$uploaderContainer.hasClass('ez-media-uploader') ||
							$uploaderContainer.closest('.ez-media-uploader')
								.length
						) {
							const $imageWrapper = $uploaderContainer.closest(
								'.directorist-form-image-upload-field'
							);
							if ($imageWrapper.length) {
								setTimeout(function () {
									triggerFn(
										'listing_img',
										'listing_img',
										$imageWrapper
											.find('.ez-media-uploader')
											.first()
									);
								}, 300);
							}
						}
					}
				});
			}
		});
	});

	fileUploadObserver.observe(document.body, {
		childList: true,
		subtree: true,
		attributes: true,
		attributeFilter: ['class'],
	});

	document.addEventListener(
		'click',
		function (e) {
			const $target = $(e.target);
			const $removeButton = $target.closest('.thumbremovelink').length
				? $target.closest('.thumbremovelink')
				: $target.hasClass('thumbremovelink')
					? $target
					: null;
			if (!$removeButton || !$removeButton.length) return;

			const $thumb = $removeButton.closest('.thumb');
			if (!$thumb.length) return;

			const $fileWrapper = $thumb.closest(
				'.directorist-form-group, .directorist-custom-field-file-upload'
			);
			if (!$fileWrapper.length) return;

			const fieldKey = resolveFileFieldKey($fileWrapper);
			if (fieldKey) {
				setTimeout(function () {
					const $hiddenInput = $fileWrapper
						.find(
							`input[type="hidden"][name="${fieldKey}"], input[type="hidden"][name="${fieldKey}[]"]`
						)
						.first();
					triggerFn(
						fieldKey,
						fieldKey,
						$hiddenInput.length
							? $hiddenInput
							: $fileWrapper
									.find('input[type="hidden"]')
									.first() || $fileWrapper
					);
				}, 400);
			}
		},
		true
	);
}

/** Get field key from data-field-key or hidden input name. */
function resolveFileFieldKey($fileWrapper) {
	let fieldKey =
		$fileWrapper.attr('data-field-key') ||
		$fileWrapper.find('[data-field-key]').first().attr('data-field-key');
	if (!fieldKey) {
		const $hiddenInput = $fileWrapper.find('input[type="hidden"]').first();
		if ($hiddenInput.length) {
			let inputName = $hiddenInput.attr('name');
			if (inputName) {
				fieldKey = inputName.includes('[')
					? inputName.split('[')[0]
					: inputName;
			}
		}
	}
	return fieldKey;
}
