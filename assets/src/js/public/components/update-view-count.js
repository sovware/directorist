/**
 * Update listings grid view count.
 */
jQuery(($) => {
	const isDynamicViewCountCacheEnabled = Boolean(
		window.directorist?.dynamic_view_count_cache
	);

	if (!isDynamicViewCountCacheEnabled) {
		return;
	}

	const updateMarkup = (viewCounts) => {
		for (const [id, count] of Object.entries(viewCounts)) {
			const $el = $(`.directorist-view-count[data-id="${id}"]`);
			const $elIcon = $el.find('.directorist-icon-mask');

			if ($elIcon.length) {
				$elIcon[0].nextSibling.textContent = count;
			} else {
				$el.text(count);
			}
		}
	};

	let ids = [];
	$('.directorist-view-count[data-id]').each((i, item) => {
		ids.push(+item.dataset.id);
	});

	if (ids.length === 0) {
		return;
	}

	const CACHE_EXPIRATION = 1000 * 60 * 60 * 5; // 5 hours.
	let cache = window.localStorage?.getItem('directorist_view_count');
	let hasCache = false;

	if (cache) {
		cache = JSON.parse(cache);
		const cachedIds = cache?.viewCount || {};
		hasCache = Object.keys(cachedIds).length;

		ids = ids.filter((id) => {
			return !(id in cachedIds);
		});

		if (
			hasCache &&
			cache?.lastUpdated &&
			Date.now() - cache.lastUpdated < CACHE_EXPIRATION
		) {
			updateMarkup(cache.viewCount);
		}

		if (!ids.length) {
			return;
		}
	}

	$.post(
		directorist.ajax_url,
		{
			action: 'directorist_update_view_count',
			nonce: directorist.directorist_nonce,
			ids: ids,
		},
		(response) => {
			if (!response.success) {
				console.warn(response.data.message);
				return;
			}

			updateMarkup(response.data.view_count);

			if (hasCache) {
				response.data.view_count = {
					...cache.viewCount,
					...response.data.view_count,
				};
			}

			window.localStorage?.setItem(
				'directorist_view_count',
				JSON.stringify({
					lastUpdated: Date.now(),
					viewCount: response.data.view_count,
				})
			);
		}
	);
});
