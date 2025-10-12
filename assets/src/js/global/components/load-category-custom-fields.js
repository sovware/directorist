export default function loadCategoryCustomFields(
	args = {
		directoryId: null,
		listingId: null,
		categoryIds: null,
		onBeforeSending: null,
		onSuccess: null,
	}
) {
	const {
		directoryId: directory_id,
		listingId: listing_id,
		categoryIds: category_ids,
		onBeforeSending,
		onSuccess,
	} = args;

	const payload = {
		action: 'directorist_load_category_custom_fields',
		directorist_nonce: directorist.directorist_nonce,
		listing_id,
		category_ids,
		directory_id,
	};

	return jQuery
		.ajax({
			method: 'POST',
			url: directorist.add_listing_data.ajaxurl,
			data: payload,
			beforeSend: onBeforeSending,
		})
		.done(onSuccess);
}
