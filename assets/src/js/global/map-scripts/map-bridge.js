const EVENT_READY = 'directorist-map-ready';
const EVENT_VIEWPORT_CHANGED = 'directorist-map-viewport-changed';
const EVENT_MARKER_CLICK = 'directorist-map-marker-click';

function ensureBridge() {
	if (window.DirectoristMapBridge && window.DirectoristMapBridge.version >= 1) {
		return window.DirectoristMapBridge;
	}

	const instances = {};

	const bridge = {
		version: 1,

		get(instanceId) {
			return instances[instanceId] || null;
		},

		replaceMarkers(instanceId, markers, options = {}) {
			const instance = this.get(instanceId);

			if (instance && typeof instance.replaceMarkers === 'function') {
				return instance.replaceMarkers(markers, options);
			}

			return false;
		},

		openPopup(instanceId, listingId, html) {
			const instance = this.get(instanceId);

			if (instance && typeof instance.openPopup === 'function') {
				return instance.openPopup(listingId, html);
			}

			return false;
		},

		register(instance) {
			if (!instance || !instance.instanceId) {
				return null;
			}

			instances[instance.instanceId] = instance;
			this.dispatchReady(instance.instanceId);

			return instance;
		},

		unregister(instanceId) {
			if (instances[instanceId]) {
				delete instances[instanceId];
			}
		},

		dispatchReady(instanceId) {
			const instance = this.get(instanceId);

			if (!instance) {
				return;
			}

			this.dispatch(EVENT_READY, {
				instanceId,
				provider: instance.provider,
				bounds:
					typeof instance.getBounds === 'function'
						? instance.getBounds()
						: null,
			});
		},

		dispatchViewportChanged(instanceId) {
			const instance = this.get(instanceId);

			if (!instance) {
				return;
			}

			this.dispatch(EVENT_VIEWPORT_CHANGED, {
				instanceId,
				provider: instance.provider,
				bounds:
					typeof instance.getBounds === 'function'
						? instance.getBounds()
						: null,
			});
		},

		dispatchMarkerClick(instanceId, listingId) {
			const instance = this.get(instanceId);

			this.dispatch(EVENT_MARKER_CLICK, {
				instanceId,
				provider: instance ? instance.provider : '',
				listingId: listingId ? parseInt(listingId, 10) : 0,
			});
		},

		dispatch(eventName, detail) {
			window.dispatchEvent(
				new CustomEvent(eventName, {
					detail,
				})
			);
		},
	};

	window.DirectoristMapBridge = bridge;

	return bridge;
}

function normalizeLongitude(longitude) {
	let normalized = parseFloat(longitude);

	if (!isFinite(normalized)) {
		return normalized;
	}

	while (normalized < -180) {
		normalized += 360;
	}

	while (normalized > 180) {
		normalized -= 360;
	}

	return normalized;
}

export function directoristMapBridge() {
	return ensureBridge();
}

export function normalizeGoogleBounds(map) {
	if (!map || typeof map.getBounds !== 'function') {
		return null;
	}

	const bounds = map.getBounds();

	if (!bounds) {
		return null;
	}

	const northEast = bounds.getNorthEast();
	const southWest = bounds.getSouthWest();

	return {
		north: northEast.lat(),
		east: northEast.lng(),
		south: southWest.lat(),
		west: southWest.lng(),
	};
}

export function normalizeLeafletBounds(map) {
	if (!map || typeof map.getBounds !== 'function') {
		return null;
	}

	const bounds = map.getBounds();

	if (!bounds) {
		return null;
	}

	return {
		north: bounds.getNorth(),
		east: normalizeLongitude(bounds.getEast()),
		south: bounds.getSouth(),
		west: normalizeLongitude(bounds.getWest()),
	};
}
