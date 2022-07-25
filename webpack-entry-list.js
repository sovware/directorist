module.exports = {
    commonEntries: {
        // Public
        // -------------------------------------------
        // JS
        ['public-main']: ["./assets/src/js/public/main.js"],
        ['checkout']: ["./assets/src/js/public/checkout.js"],
        ['search-form']: ["./assets/src/js/public/search-form.js"],
        ['range-slider']: ["./assets/src/js/public/range-slider.js"],

        //Pages
        ['all-listings']: ["./assets/src/js/public/modules/all-listings.js"],
        ['all-authors']: ["./assets/src/js/public/modules/all-authors.js"],
        ['all-location-category']: ["./assets/src/js/public/modules/all-location-category.js"],
        ['directorist-dashboard']: ["./assets/src/js/public/modules/dashboard.js"],
        ['author-profile']: ["./assets/src/js/public/modules/author-profile.js"],
        //['search-home']: ["./assets/src/js/public/modules/search-home.js"],
        ['public-add-listing']: ["./assets/src/js/public/modules/add-listing.js"],
        ['account']: ["./assets/src/js/public/modules/account.js"],
        ['single-listing']: ["./assets/src/js/public/modules/single-listing.js"],
        ['public-atmodal']: ["./assets/src/js/public/atmodal.js"],
        ['widgets']: ["./assets/src/js/public/modules/widgets.js"],

        // Admin
        // -------------------------------------------
        ['admin-main']: "./assets/src/js/admin/admin.js",
        ['admin-custom-field']: "./assets/src/js/admin/custom-field.js",
        ['admin-extension-update']: "./assets/src/js/admin/extension-update.js",
        ['admin-import-export']: "./assets/src/js/admin/import-export.js",
        ['admin-plugins']: "./assets/src/js/admin/plugins.js",
        ['admin-setup-wizard']: "./assets/src/js/admin/setup-wizard.js",
        ['admin-builder-archive']: "./assets/src/js/admin/multi-directory-archive.js",

        // Global
        // -------------------------------------------
        // JS
        ['global-main']: ["./assets/src/js/global/global.js"],
        ['add-listing']: ["./assets/src/js/global/add-listing.js"],
        ['add-listing-openstreet-map']: ["./assets/src/js/global/map-scripts/add-listing/openstreet-map.js"],
        ['add-listing-google-map']: ["./assets/src/js/global/map-scripts/add-listing/google-map.js"],
        ['global-geolocation']: ["./assets/src/js/global/map-scripts/geolocation.js"],
        ['single-listing-openstreet-map']: ["./assets/src/js/global/map-scripts/single-listing/openstreet-map.js"],
        ['single-listing-openstreet-map-widget']: ["./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js"],
        ['single-listing-google-map']: ["./assets/src/js/global/map-scripts/single-listing/google-map.js"],
        ['single-listing-google-map-widget']: ["./assets/src/js/global/map-scripts/single-listing/google-map-widget.js"],
        ['public-geolocation-widget']: ["./assets/src/js/global/map-scripts/geolocation-widget.js"],
        ['openstreet-map']: ["./assets/src/js/global/map-scripts/openstreet-map.js"],
        ['google-map']: ["./assets/src/js/global/map-scripts/map-view.js"],
        ['global-markerclusterer']: ["./assets/src/js/global/map-scripts/markerclusterer.js"],
        ['directorist-plupload']: "./assets/src/js/global/directorist-plupload.js",
    },

    vueEntries: {
        ['admin-multi-directory-builder']: "./assets/src/js/admin/multi-directory-builder.js",
        ['admin-settings-manager']: "./assets/src/js/admin/settings-manager.js",
    },
}