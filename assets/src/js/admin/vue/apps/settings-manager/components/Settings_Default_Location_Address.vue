<template>
  <div class="cptm-default-location-address">
    <div class="cptm-default-location-address__content">
      <label :for="inputId">Default address</label>
      <p>Used as the fallback map center when a listing has no address set.</p>
    </div>

    <div class="cptm-default-location-address__control">
      <div
        class="cptm-default-location-address__input-wrap"
        :class="{
          'cptm-default-location-address__input-wrap--open': showResults,
          'cptm-default-location-address__input-wrap--loading': showDetectSpinner,
        }"
      >
        <input
          :id="inputId"
          v-model="addressValue"
          type="text"
          autocomplete="off"
          placeholder="Search for a place or address"
          @focus="handleAddressFocus"
          @input="handleAddressInput"
          @keydown.enter.prevent="geocodeCurrentAddress"
          @blur="handleAddressBlur"
        />

        <button
          type="button"
          class="cptm-default-location-address__detect"
          :class="{
            'cptm-default-location-address__detect--active': showDetectSpinner,
          }"
          title="Use my current location"
          aria-label="Detect location"
          :disabled="isBusy"
          @mousedown.prevent
          @click="detectLocation"
        >
          <span
            v-if="showDetectSpinner"
            class="cptm-default-location-address__detect-spinner"
            aria-hidden="true"
          ></span>
          <svg
            v-else
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
            focusable="false"
          >
            <circle cx="12" cy="12" r="3" />
            <circle cx="12" cy="12" r="9" />
            <path d="M12 2v3M12 19v3M2 12h3M19 12h3" />
          </svg>
        </button>

        <div v-if="showResults" class="cptm-default-location-address__results">
          <button
            v-for="suggestion in suggestions"
            :key="suggestion.id"
            type="button"
            @mousedown.prevent="selectSuggestion(suggestion)"
          >
            {{ suggestion.label }}
          </button>
        </div>
      </div>

      <p
        v-if="message"
        class="cptm-default-location-address__message"
        :class="'cptm-default-location-address__message--' + messageType"
      >
        {{ message }}
      </p>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

let googleMapsPromise = null;
let googleMapsPromiseKey = "";

const coordinateFallback = (lat, lng) => `${lat}, ${lng}`;

const getGoogleMapsScriptElements = () => {
  if (typeof document === "undefined") {
    return [];
  }

  return Array.from(document.querySelectorAll("script[src]")).filter((script) =>
    /maps\.googleapis\.com\/maps\/api\/js/.test(script.src)
  );
};

const getGoogleMapsScriptKey = () => {
  const script = getGoogleMapsScriptElements()[0];

  if (!script) {
    return "";
  }

  try {
    return new URL(script.src).searchParams.get("key") || "";
  } catch (error) {
    return "";
  }
};

const formatNumber = (value) => {
  const parsed = Number(value);

  if (!Number.isFinite(parsed)) {
    return "";
  }

  return String(parsed);
};

const normalizeGoogleAddress = (result) => {
  if (!result) {
    return "";
  }

  if (!Array.isArray(result.address_components)) {
    return result.formatted_address || "";
  }

  const plusCode = ["plus_code"];
  const components = result.address_components.filter((component) => {
    const types = Array.isArray(component.types) ? component.types : [];

    return !types.some((type) => plusCode.includes(type));
  });

  if (!components.length) {
    return result.formatted_address || "";
  }

  return components.map((component) => component.long_name).join(", ");
};

export default {
  name: "settings-default-location-address",

  props: {
    config: {
      type: Object,
      default: () => ({}),
    },
  },

  data() {
    return {
      addressValue: "",
      suggestions: [],
      isSearching: false,
      isResolving: false,
      isDetecting: false,
      message: "",
      messageType: "info",
      searchTimer: null,
      blurTimer: null,
      lastReverseKey: "",
      lastResolvedAddress: "",
      lastCoordinateAddress: "",
      isUpdatingCoordinates: false,
    };
  },

  computed: {
    ...mapState(["fields"]),

    inputId() {
      return "directorist-default-location-address";
    },

    latitudeField() {
      return this.config.latitudeField || "default_latitude";
    },

    longitudeField() {
      return this.config.longitudeField || "default_longitude";
    },

    providerField() {
      return this.config.providerField || "select_listing_map";
    },

    apiKeyField() {
      return this.config.apiKeyField || "map_api_key";
    },

    latitudeValue() {
      return this.getFieldValue(this.latitudeField);
    },

    longitudeValue() {
      return this.getFieldValue(this.longitudeField);
    },

    providerValue() {
      return this.getFieldValue(this.providerField) || "openstreet";
    },

    apiKeyValue() {
      return this.getFieldValue(this.apiKeyField) || "";
    },

    isGoogleProvider() {
      return this.providerValue === "google";
    },

    isOpenStreetProvider() {
      return this.providerValue === "openstreet";
    },

    isBusy() {
      return this.isSearching || this.isResolving || this.isDetecting;
    },

    showDetectSpinner() {
      return this.isResolving || this.isDetecting;
    },

    showResults() {
      return !!this.suggestions.length;
    },
  },

  watch: {
    latitudeValue() {
      this.reverseExistingCoordinates();
    },

    longitudeValue() {
      this.reverseExistingCoordinates();
    },

    providerValue() {
      this.resetGoogleLoaderIfNeeded();
      this.suggestions = [];
      this.message = "";
      this.reverseExistingCoordinates(true);
    },

    apiKeyValue() {
      this.resetGoogleLoaderIfNeeded();

      if (this.isGoogleProvider) {
        this.reverseExistingCoordinates(true);
      }
    },
  },

  mounted() {
    this.reverseExistingCoordinates(true);
  },

  beforeDestroy() {
    window.clearTimeout(this.searchTimer);
    window.clearTimeout(this.blurTimer);
  },

  methods: {
    getFieldValue(fieldKey) {
      const field = this.fields && this.fields[fieldKey];

      return field ? field.value : "";
    },

    setFieldValue(fieldKey, value) {
      this.$emit("update-field", {
        fieldKey,
        value,
      });
    },

    numericCoordinate(value) {
      if (
        value === null ||
        typeof value === "undefined" ||
        String(value).trim() === ""
      ) {
        return null;
      }

      const parsed = Number(value);

      return Number.isFinite(parsed) ? parsed : null;
    },

    currentCoordinates() {
      const lat = this.numericCoordinate(this.latitudeValue);
      const lng = this.numericCoordinate(this.longitudeValue);

      if (lat === null || lng === null) {
        return null;
      }

      return { lat, lng };
    },

    coordinateKey(lat, lng) {
      return `${this.providerValue}:${lat}:${lng}`;
    },

    setStatus(type, message) {
      this.messageType = type;
      this.message = message;
    },

    clearStatus() {
      this.message = "";
      this.messageType = "info";
    },

    fallbackAddress(lat, lng) {
      return this.lastCoordinateAddress || coordinateFallback(lat, lng);
    },

    googleMissingKeyMessage() {
      return "Google Maps API key is required to search or resolve the default address.";
    },

    googleRequestDeniedMessage() {
      return "Google Maps rejected this request. Enable billing and allow Maps JavaScript/Geocoding for this API key.";
    },

    currentLocationDetectedLabel() {
      return "Current location detected";
    },

    googleStatusMessage(status, fallback) {
      if (status === "REQUEST_DENIED") {
        return this.googleRequestDeniedMessage();
      }

      return `${fallback}: ${status}`;
    },

    providerCanResolve() {
      if (this.isGoogleProvider && !String(this.apiKeyValue).trim()) {
        this.setStatus("warning", this.googleMissingKeyMessage());
        return false;
      }

      return true;
    },

    resetGoogleLoaderIfNeeded() {
      const apiKey = String(this.apiKeyValue).trim();

      if (googleMapsPromiseKey && googleMapsPromiseKey !== apiKey) {
        this.unloadGoogleMapsScript();
        googleMapsPromise = null;
        googleMapsPromiseKey = "";
      }
    },

    unloadGoogleMapsScript() {
      getGoogleMapsScriptElements().forEach((script) => {
        if (script.parentNode) {
          script.parentNode.removeChild(script);
        }
      });

      if (typeof window !== "undefined" && window.google) {
        try {
          delete window.google;
        } catch (error) {
          window.google = undefined;
        }
      }
    },

    loadGoogleMaps() {
      const apiKey = String(this.apiKeyValue).trim();

      if (!apiKey) {
        return Promise.reject(new Error(this.googleMissingKeyMessage()));
      }

      const loadedScriptKey = getGoogleMapsScriptKey();
      const hasGoogleMaps =
        window.google &&
        window.google.maps &&
        window.google.maps.Geocoder &&
        window.google.maps.places;

      if (hasGoogleMaps) {
        if (loadedScriptKey && loadedScriptKey !== apiKey) {
          this.unloadGoogleMapsScript();
          googleMapsPromise = null;
          googleMapsPromiseKey = "";
        } else if (!googleMapsPromiseKey || googleMapsPromiseKey === apiKey) {
          googleMapsPromiseKey = apiKey;
          return Promise.resolve(window.google.maps);
        } else {
          this.unloadGoogleMapsScript();
          googleMapsPromise = null;
          googleMapsPromiseKey = "";
        }
      }

      if (googleMapsPromise && googleMapsPromiseKey === apiKey) {
        return googleMapsPromise;
      }

      googleMapsPromiseKey = apiKey;
      googleMapsPromise = new Promise((resolve, reject) => {
        const callbackName = `directoristSettingsGoogleMaps${Date.now()}`;
        const script = document.createElement("script");

        window[callbackName] = () => {
          delete window[callbackName];
          resolve(window.google.maps);
        };

        script.async = true;
        script.defer = true;
        script.onerror = () => {
          delete window[callbackName];
          googleMapsPromise = null;
          googleMapsPromiseKey = "";
          reject(new Error("Google Maps could not be loaded."));
        };
        script.src = `https://maps.googleapis.com/maps/api/js?loading=async&libraries=places&callback=${callbackName}&key=${encodeURIComponent(apiKey)}`;
        document.head.appendChild(script);
      });

      return googleMapsPromise;
    },

    reverseExistingCoordinates(force = false) {
      if (this.isUpdatingCoordinates) {
        return;
      }

      const coordinates = this.currentCoordinates();

      if (!coordinates) {
        this.addressValue = "";
        this.lastReverseKey = "";
        this.lastCoordinateAddress = "";
        return;
      }

      const key = this.coordinateKey(coordinates.lat, coordinates.lng);

      if (!force && key === this.lastReverseKey) {
        return;
      }

      this.lastReverseKey = key;

      if (!this.providerCanResolve()) {
        this.addressValue = this.fallbackAddress(coordinates.lat, coordinates.lng);
        this.lastResolvedAddress = this.addressValue;
        return;
      }

      this.isResolving = true;

      const resolver = this.isGoogleProvider
        ? this.reverseGoogleCoordinates(coordinates)
        : this.reverseOpenStreetCoordinates(coordinates);

      resolver
        .then((address) => {
          if (key !== this.lastReverseKey) {
            return;
          }

          this.addressValue =
            address || coordinateFallback(coordinates.lat, coordinates.lng);
          this.lastResolvedAddress = this.addressValue;
          this.lastCoordinateAddress = this.addressValue;

          if (address) {
            this.clearStatus();
          } else {
            this.setStatus("warning", "Address could not be resolved from the saved coordinates.");
          }
        })
        .catch((error) => {
          if (key !== this.lastReverseKey) {
            return;
          }

          this.addressValue = this.fallbackAddress(coordinates.lat, coordinates.lng);
          this.lastResolvedAddress = this.addressValue;
          this.setStatus("warning", error.message || "Address could not be resolved from the saved coordinates.");
        })
        .finally(() => {
          this.isResolving = false;
        });
    },

    reverseOpenStreetCoordinates({ lat, lng }) {
      const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}`;

      return fetch(url)
        .then((response) => {
          if (!response.ok) {
            throw new Error("OpenStreetMap could not resolve this location.");
          }

          return response.json();
        })
        .then((data) => data.display_name || "");
    },

    reverseGoogleCoordinates({ lat, lng }) {
      return this.loadGoogleMaps().then(
        () =>
          new Promise((resolve, reject) => {
            const geocoder = new window.google.maps.Geocoder();

            geocoder.geocode({ location: { lat, lng } }, (results, status) => {
              if (status === "OK" && results && results[0]) {
                resolve(normalizeGoogleAddress(results[0]));
                return;
              }

              reject(new Error(this.googleStatusMessage(status, "Google Maps could not resolve this location")));
            });
          })
      );
    },

    handleAddressFocus() {
      if (this.addressValue && this.addressValue.length >= 3) {
        this.queueSearch();
      }
    },

    handleAddressInput() {
      this.clearStatus();

      if (!this.addressValue.trim()) {
        this.clearDefaultCoordinates();
        return;
      }

      this.queueSearch();
    },

    handleAddressBlur() {
      window.clearTimeout(this.blurTimer);
      this.blurTimer = window.setTimeout(() => {
        if (!this.addressValue || this.addressValue === this.lastResolvedAddress) {
          return;
        }

        this.geocodeCurrentAddress();
      }, 180);
    },

    queueSearch() {
      window.clearTimeout(this.searchTimer);

      if (!this.addressValue || this.addressValue.trim().length < 3) {
        this.suggestions = [];
        return;
      }

      this.searchTimer = window.setTimeout(() => {
        this.searchAddress(this.addressValue.trim());
      }, 450);
    },

    searchAddress(query) {
      if (!this.providerCanResolve()) {
        this.suggestions = [];
        return;
      }

      this.isSearching = true;

      const searcher = this.isGoogleProvider
        ? this.searchGoogleAddress(query)
        : this.searchOpenStreetAddress(query);

      searcher
        .then((suggestions) => {
          this.suggestions = suggestions;
        })
        .catch((error) => {
          this.suggestions = [];
          this.setStatus("warning", error.message || "Address search failed.");
        })
        .finally(() => {
          this.isSearching = false;
        });
    },

    searchOpenStreetAddress(query) {
      const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`;

      return fetch(url)
        .then((response) => {
          if (!response.ok) {
            throw new Error("OpenStreetMap address search failed.");
          }

          return response.json();
        })
        .then((items) =>
          (Array.isArray(items) ? items : []).slice(0, 6).map((item) => ({
            id: `${item.lat}:${item.lon}:${item.display_name}`,
            label: item.display_name,
            lat: item.lat,
            lng: item.lon,
          }))
        );
    },

    searchGoogleAddress(query) {
      return this.loadGoogleMaps().then(
        () =>
          new Promise((resolve, reject) => {
            const service = new window.google.maps.places.AutocompleteService();

            service.getPlacePredictions(
              {
                input: query,
                types: ["geocode"],
              },
              (predictions, status) => {
                if (status === "ZERO_RESULTS") {
                  resolve([]);
                  return;
                }

                if (status !== "OK") {
                  reject(new Error(this.googleStatusMessage(status, "Google address search failed")));
                  return;
                }

                resolve(
                  (predictions || []).slice(0, 6).map((prediction) => ({
                    id: prediction.place_id,
                    placeId: prediction.place_id,
                    label: prediction.description,
                  }))
                );
              }
            );
          })
      );
    },

    selectSuggestion(suggestion) {
      this.suggestions = [];

      if (suggestion.lat && suggestion.lng) {
        this.applyCoordinates({
          lat: suggestion.lat,
          lng: suggestion.lng,
          address: suggestion.label,
        });
        return;
      }

      if (suggestion.placeId) {
        this.geocodeGooglePlace(suggestion.placeId, suggestion.label);
      }
    },

    geocodeCurrentAddress() {
      const query = this.addressValue.trim();

      if (!query || query === this.lastResolvedAddress) {
        return;
      }

      if (!this.providerCanResolve()) {
        return;
      }

      this.isResolving = true;
      this.suggestions = [];

      const geocoder = this.isGoogleProvider
        ? this.geocodeGoogleAddress(query)
        : this.geocodeOpenStreetAddress(query);

      geocoder
        .then((result) => {
          this.applyCoordinates(result);
        })
        .catch((error) => {
          this.setStatus("warning", error.message || "Address could not be resolved.");
        })
        .finally(() => {
          this.isResolving = false;
        });
    },

    clearDefaultCoordinates() {
      window.clearTimeout(this.searchTimer);
      window.clearTimeout(this.blurTimer);
      this.suggestions = [];
      this.lastReverseKey = "";
      this.lastResolvedAddress = "";
      this.lastCoordinateAddress = "";

      const hasLatitude =
        this.latitudeValue !== null &&
        typeof this.latitudeValue !== "undefined" &&
        String(this.latitudeValue) !== "";
      const hasLongitude =
        this.longitudeValue !== null &&
        typeof this.longitudeValue !== "undefined" &&
        String(this.longitudeValue) !== "";

      if (!hasLatitude && !hasLongitude) {
        return;
      }

      this.isUpdatingCoordinates = true;
      this.setFieldValue(this.latitudeField, "");
      this.setFieldValue(this.longitudeField, "");
      this.$nextTick(() => {
        this.isUpdatingCoordinates = false;
      });
    },

    geocodeOpenStreetAddress(query) {
      return this.searchOpenStreetAddress(query).then((suggestions) => {
        if (!suggestions.length) {
          throw new Error("No matching address found.");
        }

        return suggestions[0];
      });
    },

    geocodeGoogleAddress(query) {
      return this.loadGoogleMaps().then(
        () =>
          new Promise((resolve, reject) => {
            const geocoder = new window.google.maps.Geocoder();

            geocoder.geocode({ address: query }, (results, status) => {
              if (status === "OK" && results && results[0]) {
                resolve(this.googleResultToLocation(results[0]));
                return;
              }

              reject(new Error(this.googleStatusMessage(status, "Google Maps could not resolve this address")));
            });
          })
      );
    },

    geocodeGooglePlace(placeId, fallbackAddress) {
      if (!this.providerCanResolve()) {
        return;
      }

      this.isResolving = true;

      this.loadGoogleMaps()
        .then(
          () =>
            new Promise((resolve, reject) => {
              const geocoder = new window.google.maps.Geocoder();

              geocoder.geocode({ placeId }, (results, status) => {
                if (status === "OK" && results && results[0]) {
                  const location = this.googleResultToLocation(results[0]);
                  location.address = location.address || fallbackAddress;
                  resolve(location);
                  return;
                }

                reject(new Error(this.googleStatusMessage(status, "Google Maps could not resolve this place")));
              });
            })
        )
        .then((result) => {
          this.applyCoordinates(result);
        })
        .catch((error) => {
          this.setStatus("warning", error.message || "Address could not be resolved.");
        })
        .finally(() => {
          this.isResolving = false;
        });
    },

    googleResultToLocation(result) {
      const location = result.geometry && result.geometry.location;

      if (!location) {
        throw new Error("Google Maps did not return coordinates for this address.");
      }

      return {
        address: normalizeGoogleAddress(result),
        lat: location.lat(),
        lng: location.lng(),
      };
    },

    applyCoordinates(result) {
      const lat = formatNumber(result.lat);
      const lng = formatNumber(result.lng);

      if (!lat || !lng) {
        this.setStatus("warning", "Address did not return valid coordinates.");
        return;
      }

      this.isUpdatingCoordinates = true;
      this.setFieldValue(this.latitudeField, lat);
      this.setFieldValue(this.longitudeField, lng);
      this.$nextTick(() => {
        this.isUpdatingCoordinates = false;
        this.lastReverseKey = this.coordinateKey(lat, lng);
      });

      this.addressValue = result.address || coordinateFallback(lat, lng);
      this.lastResolvedAddress = this.addressValue;
      this.lastCoordinateAddress = this.addressValue;
      this.clearStatus();
    },

    isInsecureLocationOrigin() {
      return typeof window !== "undefined" && window.isSecureContext === false;
    },

    insecureLocationMessage() {
      return "Location detection needs HTTPS or localhost. Open this admin over HTTPS to allow the browser permission prompt.";
    },

    blockedLocationMessage() {
      return "Location is blocked for this site. Allow location from the browser address bar, then try again.";
    },

    currentLocationPermissionState() {
      if (!navigator.permissions || !navigator.permissions.query) {
        return Promise.resolve("");
      }

      return navigator.permissions
        .query({ name: "geolocation" })
        .then((permission) => permission.state)
        .catch(() => "");
    },

    geolocationErrorMessage(error) {
      const browserMessage =
        error && error.message ? String(error.message) : "";

      if (
        this.isInsecureLocationOrigin() ||
        /secure origin|secure context|https/i.test(browserMessage)
      ) {
        return this.insecureLocationMessage();
      }

      if (!error || typeof error.code === "undefined") {
        return "Location could not be detected.";
      }

      if (error.code === error.PERMISSION_DENIED) {
        return this.blockedLocationMessage();
      }

      if (error.code === error.POSITION_UNAVAILABLE) {
        return "Your current location is unavailable.";
      }

      if (error.code === error.TIMEOUT) {
        return "Location detection timed out.";
      }

      return "Location could not be detected.";
    },

    detectLocation() {
      if (!navigator.geolocation) {
        this.setStatus("warning", "Your browser does not support location detection.");
        return;
      }

      if (this.isInsecureLocationOrigin()) {
        this.setStatus("warning", this.insecureLocationMessage());
        return;
      }

      this.isDetecting = true;

      this.currentLocationPermissionState().then((permissionState) => {
        if (permissionState === "denied") {
          this.isDetecting = false;
          this.setStatus("warning", this.blockedLocationMessage());
          return;
        }

        navigator.geolocation.getCurrentPosition(
        (position) => {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;

          if (!this.providerCanResolve()) {
            this.applyCoordinates({
              address: this.currentLocationDetectedLabel(),
              lat,
              lng,
            });
            this.setStatus("warning", "Location detected. Add a Google Maps API key to resolve the address.");
            this.isDetecting = false;
            return;
          }

          const resolver = this.isGoogleProvider
            ? this.reverseGoogleCoordinates({ lat, lng })
            : this.reverseOpenStreetCoordinates({ lat, lng });

          resolver
            .then((address) => {
              this.applyCoordinates({
                address,
                lat,
                lng,
              });
            })
            .catch((error) => {
              this.applyCoordinates({
                address: this.currentLocationDetectedLabel(),
                lat,
                lng,
              });
              this.setStatus("warning", error.message || "Location detected, but address could not be resolved.");
            })
            .finally(() => {
              this.isDetecting = false;
            });
        },
        (error) => {
          this.isDetecting = false;
          this.setStatus("warning", this.geolocationErrorMessage(error));
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 60000,
        }
        );
      });
    },
  },
};
</script>
