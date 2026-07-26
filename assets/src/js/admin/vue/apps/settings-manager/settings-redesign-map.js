const clone = (value) => JSON.parse(JSON.stringify(value || {}));

const hasOwn = (object, key) =>
	Object.prototype.hasOwnProperty.call(object || {}, key);

const normalizeLayoutFieldKey = (fieldKey) => {
	if (Array.isArray(fieldKey)) {
		return fieldKey[0] || '';
	}

	return fieldKey;
};

const svgIcon = (content) =>
	`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">${content}</svg>`;

const SETTINGS_REDESIGN_ICONS = {
	directory: svgIcon(
		'<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4M10 10h4M10 14h4M10 18h4"/>',
	),
	monetization: svgIcon(
		'<rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/>',
	),
	notifications: svgIcon(
		'<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10 21a2 2 0 0 0 4 0"/>',
	),
	appearance: svgIcon(
		'<circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>',
	),
	sitePages: svgIcon(
		'<rect x="2" y="3" width="20" height="18" rx="2"/><path d="M2 9h20"/>',
	),
	extensions: svgIcon(
		'<path d="M4 7v10c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V7M4 7l8-4 8 4M4 7l8 4 8-4M12 11v10"/>',
	),
	tools: svgIcon(
		'<path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/>',
	),
	help: svgIcon(
		'<circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>',
	),
	general: svgIcon(
		'<path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6"/>',
	),
	singleListing: svgIcon(
		'<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>',
	),
	submissions: svgIcon(
		'<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/>',
	),
	map: svgIcon(
		'<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/>',
	),
	reviews: svgIcon(
		'<path d="m12 2 3 6.5 7 .8-5 4.8 1.3 7L12 17.8 5.4 21l1.3-7-5-4.8 7-.8z"/>',
	),
	listings: svgIcon(
		'<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h10M7 16h6"/>',
	),
	taxonomies: svgIcon(
		'<path d="M4 6h16M4 12h16M4 18h16"/><path d="M8 6v12M16 6v12"/>',
	),
	search: svgIcon(
		'<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
	),
	users: svgIcon(
		'<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
	),
	currency: svgIcon(
		'<path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
	),
	featured: svgIcon(
		'<circle cx="12" cy="12" r="10"/><path d="m16 12-4-4-4 4M12 16V8"/>',
	),
	gateways: svgIcon(
		'<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>',
	),
	channels: svgIcon(
		'<circle cx="12" cy="12" r="2"/><path d="M4.93 19.07a10 10 0 0 1 0-14.14M7.76 16.24a6 6 0 0 1 0-8.48M16.24 7.76a6 6 0 0 1 0 8.48M19.07 4.93a10 10 0 0 1 0 14.14"/>',
	),
	events: svgIcon(
		'<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
	),
	brand: svgIcon(
		'<path d="M12 2.7s5.5 5 5.5 9.3a5.5 5.5 0 1 1-11 0C6.5 7.7 12 2.7 12 2.7Z"/>',
	),
	badges: svgIcon(
		'<circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/>',
	),
	pages: svgIcon(
		'<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>',
	),
	seo: svgIcon('<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>'),
	schema: svgIcon(
		'<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5M3 12a9 3 0 0 0 18 0"/>',
	),
	maintenance: svgIcon(
		'<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
	),
	docs: svgIcon(
		'<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/>',
	),
};

const FIELD_OVERRIDES = {
	enable_multi_directory: {
		label: 'Enable multi-directory',
		description:
			'Build separate directory types like jobs and real estate side by side, each with their own fields.',
		componets: {
			link: {
				show: false,
			},
		},
	},
	new_user_registration: {
		label: 'Allow new user registration',
	},
	enable_email_verification: {
		label: 'Require email verification',
		description:
			'New users must click a verification link before they can post.',
	},
	delete_expired_listing_permanently: {
		label: 'Permanently delete trashed listings',
		description:
			'Removes listings from your database after they have been in trash for the time set below.',
	},
	delete_expired_listings_after: {
		label: 'Delete after',
		description: '',
	},
	count_loggedin_user: {
		label: 'Count views from logged-in users',
	},
	dynamic_view_count_cache: {
		label: 'Real-time counts with cache plugins',
		description:
			'Keeps view counts accurate when using W3 Total Cache or LiteSpeed Cache.',
	},
	enable_archive_template: {
		label: 'Use WordPress default archive',
		description:
			"Turns off Directorist's custom archive pages and uses WordPress built-in ones instead.",
	},
	category_base: {
		label: 'Category URL slug',
		description: '',
	},
	location_base: {
		label: 'Location URL slug',
		description: '',
	},
	tag_base: {
		label: 'Tag URL slug',
		description: '',
	},
	all_listing_columns: {
		type: 'select',
		preview: {},
	},
	single_listing_template: {
		label: 'Template',
	},
	disable_single_listing: {
		label: 'Disable single listing page',
		description:
			'Hides the listing detail page entirely. Useful if you only want grid view.',
	},
	restrict_single_listing_for_logged_in_user: {
		label: 'Show only to logged-in users',
		description: 'Visitors must sign in to view full listing details.',
	},
	atbdp_listing_slug: {
		label: 'Listing URL slug',
		description:
			'Appears in every listing URL, like yoursite.com/directory/business-name.',
	},
	single_listing_slug_with_directory_type: {
		label: 'Include directory type in URL',
		description:
			'Adds the directory type, e.g. jobs or real-estate, into the URL path.',
	},
	dsiplay_slider_single_page: {
		label: 'Show slider image',
		description: '',
	},
	single_slider_image_size: {
		label: 'Image fit',
		description: '',
	},
	single_slider_background_type: {
		label: 'Background type',
		description: '',
	},
	single_slider_background_color: {
		label: 'Background color',
		description:
			"Shows around the image when it doesn't fill the slider area.",
	},
	guest_listings: {
		label: 'Allow guest submissions',
		description: 'Visitors can add a listing without creating an account.',
	},
	guest_email_label: {
		label: 'Guest email field label',
		description: '',
	},
	guest_email_placeholder: {
		label: 'Guest email placeholder',
		description: '',
	},
	submission_confirmation: {
		label: 'Show confirmation message',
		description: 'Display a message to listing owners after they submit.',
	},
	pending_confirmation_msg: {
		label: 'Pending review message',
		description: 'Shown when a listing is waiting for admin approval.',
	},
	publish_confirmation_msg: {
		label: 'Published message',
		description: 'Shown when a listing is approved and live.',
	},
	select_listing_map: {
		label: 'Provider',
		description: 'OpenStreetMap is free. Google Maps needs an API key.',
	},
	map_api_key: {
		label: 'Google Maps API key',
		description: 'Required only when Google Maps is selected.',
		placeholder: 'Enter Google Maps API key',
	},
	default_latitude: {
		label: 'Default latitude',
		description:
			'Used as the fallback map center when no listing location is available.',
	},
	default_longitude: {
		label: 'Default longitude',
		description: 'Used with the default latitude to center map views.',
	},
	use_def_lat_long: {
		label: 'Apply default location to all listings on the map',
		description:
			'Use the default address above for any listing that has no map pin set.',
	},
	listings_map_height: {
		label: 'Map height',
		description: '',
	},
	map_zoom_level: {
		label: 'Zoom on single listing',
		description: '0 is fully zoomed out, 22 is closest.',
	},
	map_view_zoom_level: {
		label: 'Zoom on map view',
		description:
			'Zoom for the map that shows many listings. Lower is more zoomed out.',
	},
	display_map_info: {
		label: 'Show info window',
		description: '',
	},
	display_image_map: {
		label: 'Image',
		description: '',
	},
	display_user_avatar_map: {
		label: 'Author avatar',
		description: '',
	},
	display_favorite_badge_map: {
		label: 'Favorite badge',
		description: '',
	},
	display_title_map: {
		label: 'Title',
		description: '',
	},
	display_address_map: {
		label: 'Address',
		description: '',
	},
	display_phone_map: {
		label: 'Phone',
		description: '',
	},
	display_price_map: {
		label: 'Price',
		description: '',
	},
	display_review_map: {
		label: 'Rating',
		description: '',
	},
	display_direction_map: {
		label: 'Get directions link',
		description: '',
	},
	marker_clustering: {
		label: 'Cluster nearby pins',
		description: '',
	},
	country_restriction: {
		label: 'Restrict to specific countries',
		description: 'Limits address autocomplete to selected countries.',
	},
	restricted_countries: {
		label: 'Selected countries',
		description: 'Hold Cmd / Ctrl to select more than one.',
	},
	enable_review: {
		label: 'Allow user reviews',
		description: '',
	},
	enable_owner_review: {
		label: 'Allow listing owners to review their own listings',
		description: '',
	},
	guest_review: {
		label: 'Allow guest reviews',
		description:
			'Visitors can leave a review without signing in. Subject to moderation.',
	},
	review_enable_reply: {
		label: 'Allow replies to reviews',
		description: '',
	},
	approve_immediately: {
		label: 'Auto-approve submitted reviews',
		description: 'New reviews appear immediately without admin approval.',
	},
	review_num: {
		label: 'Reviews per page',
		description: 'Keep under 10 for best performance.',
	},
	g_currency: {
		label: 'Currency code',
		description: 'Three-letter ISO code (e.g. USD, EUR, GBP, BDT).',
	},
	g_currency_position: {
		label: 'Currency position',
		description: '',
	},
	payment_currency: {
		label: 'Currency code',
		description: 'Three-letter ISO code (e.g. USD, EUR, GBP, BDT).',
	},
	payment_currency_position: {
		label: 'Currency position',
		description: '',
	},
	payment_thousand_separator: {
		label: 'Thousands separator',
		description: 'Character between thousands, e.g. a comma in 1,000.',
	},
	payment_decimal_separator: {
		label: 'Decimal separator',
		description: 'Character before the decimals, e.g. a period in 9.99.',
	},
	enable_monetization: {
		label: 'Enable monetization',
		description:
			'Master switch for paid listings and payment gateways. Turn off to make all listings free.',
	},
	enable_featured_listing: {
		label: 'Allow featured upgrades',
		description:
			'Listing owners can pay to highlight their listing on search and category pages.',
	},
	featured_listing_price: {
		label: 'Featured fee',
		description: 'What a user pays to feature a single listing.',
	},
	featured_listing_time: {
		label: 'Featured duration',
		description:
			'How long a listing stays featured before it returns to normal.',
	},
	featured_listing_desc: {
		label: 'Description at checkout',
		description: 'Shown to listing owners when they upgrade.',
	},
	active_gateways: {
		label: 'Payment Methods',
		description:
			'Check the gateway(s) you would like to use to collect payment from your users. A user will be use any of the active gateways during the checkout process.',
	},
	default_gateway: {
		label: 'Default gateway',
		description: 'Pre-selected at checkout.',
	},
	bank_transfer_title: {
		label: 'Gateway title',
		description: 'Shown to users at checkout.',
	},
	bank_transfer_description: {
		label: 'Gateway description',
		description: '',
	},
	bank_transfer_instruction: {
		label: 'Bank instructions',
		description:
			'Shown after order is placed. Use ==ORDER_ID== as a placeholder.',
	},
	email_to_expire_day: {
		label: 'Send expiry notice',
		description: 'Heads-up email before a listing expires.',
	},
	email_renewal_day: {
		label: 'Send renewal reminder',
		description: 'Follow-up email after a listing has expired.',
	},
	disable_email_notification: {
		label: 'Enable email notifications',
		description: 'Master switch for all outgoing email.',
	},
	web_push_notify_admin: {
		label: 'Enable web push notifications',
		description:
			'Send browser push to admins and listing owners who have opted in.',
	},
	web_push_notify_user: {
		label: 'Web push listing owner events',
		description:
			'Events that send browser push notifications to listing owners.',
	},
	email_from_name: {
		label: 'Sender name',
		description: 'Appears as the sender on every email.',
	},
	email_from_email: {
		label: 'Sender email',
		description: 'Reply address for all outgoing email.',
	},
	admin_email_lists: {
		label: 'Admin email addresses',
		description: 'Comma-separated. Where admin notifications are sent.',
	},
	allow_email_header: {
		label: 'Show email header',
		description: 'Branded header at the top of every email.',
	},
	email_header_color: {
		label: 'Header color',
		description: '',
	},
	add_listing_page: {
		label: 'Add listing page',
	},
	all_listing_page: {
		label: 'All listings page',
	},
	user_dashboard: {
		label: 'Dashboard page',
	},
	signin_signup_page: {
		label: 'Sign in & signup page',
	},
	author_profile_page: {
		label: 'Author profile page',
	},
	all_categories_page: {
		label: 'All categories page',
	},
	single_category_page: {
		label: 'Single category page',
	},
	all_locations_page: {
		label: 'All locations page',
	},
	single_location_page: {
		label: 'Single location page',
	},
	single_tag_page: {
		label: 'Single tag page',
	},
	search_listing: {
		label: 'Search form page',
	},
	search_result_page: {
		label: 'Search results page',
	},
	checkout_page: {
		label: 'Checkout page',
	},
	payment_receipt_page: {
		label: 'Payment receipt page',
	},
	transaction_failure_page: {
		label: 'Transaction failure page',
	},
	privacy_policy: {
		label: 'Privacy policy page',
		description: '',
	},
	terms_conditions: {
		label: 'Terms and conditions page',
		description: '',
	},
	regenerate_pages: {
		label: 'Regenerate missing pages',
		description:
			'Creates any pages from the list above that are not currently mapped.',
		buttonLabel: 'Regenerate',
		buttonLabelOnProcessing:
			'<i class="fas fa-circle-notch fa-spin"></i> Regenerating',
	},
	listing_import_button: {
		label: 'Import listings',
		description:
			'Upload a CSV file. Map columns to listing fields in the next step.',
		buttonLabel: 'Import CSV',
	},
	listing_export_button: {
		label: 'Export listings',
		description: '',
		buttonLabel: 'Export CSV',
	},
	import_settings: {
		label: 'Import settings',
		description:
			'Upload a JSON file exported from another Directorist site.',
		buttonLabel: 'Import JSON',
	},
	export_settings: {
		label: 'Export settings',
		description: '',
		buttonLabel: 'Export JSON',
	},
	restore_default_settings: {
		label: 'Restore defaults',
		description: 'Resets every setting. Listings are not affected.',
		buttonLabel: 'Restore defaults',
	},
	atbdp_enable_seo: {
		label: 'Enable Directorist SEO',
		description: '',
	},
	add_listing_page_meta_title: {
		label: 'Add listing meta title',
		description: '',
	},
	add_listing_page_meta_desc: {
		label: 'Add listing meta description',
		description: '',
	},
	all_listing_meta_title: {
		label: 'All listings meta title',
		description: '',
	},
	all_listing_meta_desc: {
		label: 'All listings meta description',
		description: '',
	},
	dashboard_meta_title: {
		label: 'Dashboard meta title',
		description: '',
	},
	dashboard_meta_desc: {
		label: 'Dashboard meta description',
		description: '',
	},
	author_profile_meta_title: {
		label: 'Author profile meta title',
		description: '',
	},
	author_page_meta_desc: {
		label: 'Author profile meta description',
		description: '',
	},
	category_meta_title: {
		label: 'Categories meta title',
		description: '',
	},
	category_meta_desc: {
		label: 'Categories meta description',
		description: '',
	},
	single_category_meta_title: {
		label: 'Single category meta title',
		description: '',
	},
	single_category_meta_desc: {
		label: 'Single category meta description',
		description: '',
	},
	all_locations_meta_title: {
		label: 'All locations meta title',
		description: '',
	},
	all_locations_meta_desc: {
		label: 'All locations meta description',
		description: '',
	},
	single_locations_meta_title: {
		label: 'Single location meta title',
		description: '',
	},
	single_locations_meta_desc: {
		label: 'Single location meta description',
		description: '',
	},
	registration_meta_title: {
		label: 'Registration meta title',
		description: '',
	},
	registration_meta_desc: {
		label: 'Registration meta description',
		description: '',
	},
	login_meta_title: {
		label: 'Login meta title',
		description: '',
	},
	login_meta_desc: {
		label: 'Login meta description',
		description: '',
	},
	homepage_meta_title: {
		label: 'Search home meta title',
		description: '',
	},
	homepage_meta_desc: {
		label: 'Search home meta description',
		description: '',
	},
	meta_title_for_search_result: {
		label: 'Use search-friendly title on search results',
		description:
			"Builds the meta title from the visitor's search terms instead of the static title below.",
	},
	search_result_meta_title: {
		label: 'Custom search result meta title',
		description: '',
	},
	search_result_meta_desc: {
		label: 'Search result meta description',
		description: '',
	},
	enable_schema_markup: {
		label: 'Enable schema markup',
		description:
			'Outputs JSON-LD on listing pages. Turn off to stop emitting structured data entirely.',
	},
	apply_schema_markup: {
		schema: 'Apply schema to',
		options: [
			{
				label: 'All directories',
				description:
					'Use one schema type everywhere. Pick this if Multi-directory is off.',
				value: 'all-directory',
			},
			{
				label: 'Per directory',
				description: 'Set a different schema type for each directory.',
				value: 'per-directory',
			},
		],
	},
	directory_schema_type_global: {
		label: 'Schema type',
		description: 'Used when a listing has no specific type set.',
	},
	atbdp_enable_cache: {
		label: 'Enable cache',
		description:
			'Stores frequently used queries in memory for faster page loads.',
	},
	atbdp_reset_cache: {
		label: 'Reset cache',
		description:
			'Clear all cached data. Useful after making large changes.',
		showIf: null,
		show_if: null,
		'show-if': null,
	},
	script_debugging: {
		label: 'Script debugging',
		description:
			'Loads unminified JavaScript files. Use only when investigating bugs.',
	},
	enable_uninstall: {
		label: 'Remove all data on uninstall',
		description:
			'Permanently deletes every listing, order, review, and setting if you delete the plugin. This cannot be undone.',
	},
	extension_promotion: {
		title: 'Installed extensions',
		description:
			'No extension settings available yet. Each extension you install can add its own settings section here.',
		browseTitle: 'Browse extensions',
		browseDescription:
			'30+ extensions available including PayPal, Stripe, Live Chat, Universal Search, Booking, and Pricing Plans.',
		browseButtonLabel: 'View directory',
		browseUrl:
			'/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-extension',
	},
	brand_color: {
		label: 'Brand color',
		description:
			'Used for primary buttons, links, and accents on the directory front-end.',
	},
	listings_view_as_items: {
		label: 'View type',
		description: '',
	},
	listings_sort_by_items: {
		label: 'Sort options',
		description: '',
	},
	preview_image_quality: {
		label: 'Image quality',
	},
	way_to_show_preview: {
		label: 'Image fit',
		description:
			'Controls how listing preview images fill their card area.',
	},
	crop_width: {
		label: 'Width',
		description: '',
	},
	crop_height: {
		label: 'Height',
		description: '',
	},
	prv_container_size_by: {
		label: 'Unit',
		description: '',
	},
	prv_background_type: {
		label: 'Background',
		description: '',
	},
	prv_background_color: {
		label: 'Background color',
		description: '',
	},
	gallery_crop_width: {
		label: 'Image width',
		description: '',
	},
	gallery_crop_height: {
		label: 'Image height',
		description: '',
	},
};

const SUPPRESSED_REDESIGN_FIELDS = new Set([
	'atbdp_reset_cache',
	'gallery_crop_width',
	'gallery_crop_height',
	'web_push_events_note',
	'web_push_templates_note',
]);

const WEB_PUSH_ADMIN_EVENTS = [
	'order_created',
	'order_completed',
	'payment_received',
	'listing_submitted',
	'listing_published',
	'listing_edited',
	'listing_deleted',
	'listing_renewed',
	'listing_contact_form',
	'listing_review',
];

const WEB_PUSH_OWNER_EVENTS = [
	'order_created',
	'order_completed',
	'payment_received',
	'listing_submitted',
	'listing_published',
	'listing_edited',
	'listing_deleted',
	'listing_renewed',
	'listing_to_expire',
	'listing_expired',
	'remind_to_renew',
	'listing_contact_form',
	'listing_review',
];

const WEB_PUSH_TEMPLATE_FIELDS = [
	...WEB_PUSH_ADMIN_EVENTS.flatMap((eventKey) => [
		`web_push_admin_${eventKey}_title`,
		`web_push_admin_${eventKey}_message`,
	]),
	...WEB_PUSH_OWNER_EVENTS.flatMap((eventKey) => [
		`web_push_owner_${eventKey}_title`,
		`web_push_owner_${eventKey}_message`,
	]),
];

const FIELD_GROUPS = {
	directoriesGeneral: [
		{
			key: 'multi_directory',
			title: 'Multi-directory',
			fields: ['enable_multi_directory'],
		},
		{
			key: 'accounts',
			title: 'Accounts',
			fields: ['new_user_registration', 'enable_email_verification'],
		},
		{
			key: 'listing_lifecycle',
			title: 'Listing lifecycle',
			fields: [
				'delete_expired_listing_permanently',
				'delete_expired_listings_after',
			],
		},
		{
			key: 'view_tracking',
			title: 'View tracking',
			advanced: true,
			fields: ['count_loggedin_user', 'dynamic_view_count_cache'],
		},
		{
			key: 'archive_pages',
			title: 'Archive pages',
			advanced: true,
			fields: [
				'enable_archive_template',
				'category_base',
				'location_base',
				'tag_base',
			],
		},
	],

	directoriesListingsPage: [
		{
			key: 'archive_layout',
			title: 'Archive layout',
			fields: [
				'all_listing_layout',
				'all_listing_columns',
				'all_listing_page_items',
				'pagination_type',
			],
		},
		{
			key: 'archive_search_filters',
			title: 'Search and filters',
			fields: [
				'listing_hide_top_search_bar',
				'listings_sidebar_filter_text',
				'listings_reset_text',
				'listings_sidebar_reset_text',
				'listings_apply_text',
			],
		},
		{
			key: 'archive_header',
			title: 'Header and controls',
			fields: [
				'display_listings_header',
				'listing_filters_button',
				'listings_filter_button_text',
				'display_listings_count',
				'all_listing_title',
			],
		},
		{
			key: 'archive_sorting_view',
			title: 'View and sorting',
			fields: [
				'listings_view_as_items',
				'default_listing_view',
				'display_sort_by',
				'sort_by_text',
				'listings_sort_by_items',
			],
		},
		{
			key: 'archive_preview_image',
			title: 'Preview image',
			fields: [
				'preview_image_quality',
				'way_to_show_preview',
				'crop_width',
				'crop_height',
				'prv_container_size_by',
				'prv_background_type',
				'prv_background_color',
			],
		},
	],

	directoriesSingleListing: [
		{
			key: 'template_visibility',
			title: 'Template and visibility',
			fields: [
				'single_listing_template',
				'disable_single_listing',
				'restrict_single_listing_for_logged_in_user',
			],
		},
		{
			key: 'permalink',
			title: 'Permalink',
			fields: [
				'atbdp_listing_slug',
				'single_listing_slug_with_directory_type',
			],
		},
		{
			key: 'slider_image',
			title: 'Slider image',
			fields: [
				'dsiplay_slider_single_page',
				'single_slider_image_size',
				'single_slider_background_type',
				'single_slider_background_color',
			],
			advancedFields: [
				'single_slider_image_size',
				'single_slider_background_type',
				'single_slider_background_color',
			],
		},
	],

	directoriesSubmissions: [
		{
			key: 'guest_submissions',
			title: 'Guest submissions',
			fields: [
				'guest_listings',
				'guest_email_label',
				'guest_email_placeholder',
			],
		},
		{
			key: 'confirmation_messages',
			title: 'Confirmation messages',
			fields: [
				'submission_confirmation',
				'pending_confirmation_msg',
				'publish_confirmation_msg',
			],
			advancedLabel: 'Customize message wording',
			advancedFields: [
				'pending_confirmation_msg',
				'publish_confirmation_msg',
			],
		},
	],

	directoriesCategoryLocation: [
		{
			key: 'categories_display',
			title: 'Categories',
			fields: [
				'display_categories_as',
				'categories_column_number',
				'categories_depth_number',
				'order_category_by',
				'sort_category_by',
				'display_listing_count',
				'hide_empty_categories',
			],
		},
		{
			key: 'locations_display',
			title: 'Locations',
			fields: [
				'display_locations_as',
				'locations_column_number',
				'locations_depth_number',
				'order_location_by',
				'sort_location_by',
				'display_location_listing_count',
				'hide_empty_locations',
			],
		},
	],

	directoriesMap: [
		{
			key: 'map_provider',
			title: 'Map provider',
			fields: ['select_listing_map', 'map_api_key'],
		},
		{
			key: 'default_location',
			title: 'Default location',
			fields: [
				'default_latitude',
				'default_longitude',
				'use_def_lat_long',
			],
			defaultLocationAddress: {
				beforeField: 'use_def_lat_long',
				latitudeField: 'default_latitude',
				longitudeField: 'default_longitude',
				providerField: 'select_listing_map',
				apiKeyField: 'map_api_key',
			},
			hiddenFields: ['default_latitude', 'default_longitude'],
		},
		{
			key: 'zoom_size',
			title: 'Zoom and size',
			fields: [
				'listings_map_height',
				'map_zoom_level',
				'map_view_zoom_level',
			],
			advancedLabel: 'Advanced',
			advancedFields: ['map_zoom_level', 'map_view_zoom_level'],
		},
		{
			key: 'pins_coverage',
			title: 'Pins and coverage',
			fields: [
				'marker_clustering',
				'country_restriction',
				'restricted_countries',
			],
			showIf: {
				where: 'select_listing_map',
				conditions: [
					{
						key: 'value',
						compare: '=',
						value: 'google',
					},
				],
			},
		},
		{
			key: 'info_window',
			title: 'Info window on map',
			description:
				'What appears in the popup when someone clicks a map pin.',
			fields: [
				'display_map_info',
				'display_image_map',
				'display_user_avatar_map',
				'display_favorite_badge_map',
				'display_title_map',
				'display_address_map',
				'display_phone_map',
				'display_price_map',
				'display_review_map',
				'display_direction_map',
			],
			advancedLabel: 'What to show in the info window',
			advancedFields: [
				'display_image_map',
				'display_user_avatar_map',
				'display_favorite_badge_map',
				'display_title_map',
				'display_address_map',
				'display_phone_map',
				'display_price_map',
				'display_review_map',
				'display_direction_map',
			],
		},
	],

	directoriesReviews: [
		{
			key: 'reviews',
			title: 'Reviews',
			fields: [
				'enable_review',
				'enable_owner_review',
				'guest_review',
				'review_enable_reply',
				'approve_immediately',
				'review_num',
			],
		},
	],

	searchForm: [
		{
			key: 'search_hero',
			title: 'Search form',
			fields: ['search_title', 'search_subtitle', 'search_listing_text'],
		},
		{
			key: 'search_filters',
			title: 'Filters',
			fields: [
				'search_more_filter',
				'search_more_filters',
				'search_filters',
				'search_reset_text',
				'search_apply_filter',
			],
		},
		{
			key: 'popular_categories',
			title: 'Popular categories',
			fields: [
				'show_popular_category',
				'popular_cat_title',
				'popular_cat_num',
			],
		},
	],

	searchResults: [
		{
			key: 'search_results_layout',
			title: 'Results layout',
			fields: [
				'search_result_layout',
				'search_listing_columns',
				'search_posts_num',
			],
		},
		{
			key: 'search_results_filters',
			title: 'Search and filters',
			fields: [
				'search_result_hide_top_search_bar',
				'search_result_sidebar_filter_text',
				'sresult_reset_text',
				'sresult_sidebar_reset_text',
				'sresult_apply_text',
			],
		},
		{
			key: 'search_results_header',
			title: 'Header and controls',
			fields: [
				'search_header',
				'search_result_filters_button_display',
				'search_result_filter_button_text',
				'display_search_result_listings_count',
				'search_result_listing_title',
			],
		},
		{
			key: 'search_results_sorting_view',
			title: 'View and sorting',
			fields: [
				'search_view_as_items',
				'search_sort_by',
				'search_sortby_text',
				'search_sort_by_items',
			],
		},
	],

	userRegistration: [
		{
			key: 'registration_username',
			title: 'Username',
			fields: ['reg_username'],
		},
		{
			key: 'registration_password',
			title: 'Password',
			fields: [
				'display_password_reg',
				'reg_password',
				'require_password_reg',
			],
		},
		{
			key: 'registration_email',
			title: 'Email',
			fields: ['reg_email'],
		},
		{
			key: 'registration_website',
			title: 'Website',
			fields: [
				'display_website_reg',
				'reg_website',
				'require_website_reg',
			],
		},
		{
			key: 'registration_first_name',
			title: 'First Name',
			fields: ['display_fname_reg', 'reg_fname', 'require_fname_reg'],
		},
		{
			key: 'registration_last_name',
			title: 'Last Name',
			fields: ['display_lname_reg', 'reg_lname', 'require_lname_reg'],
		},
		{
			key: 'registration_about_bio',
			title: 'About/Bio',
			fields: ['display_bio_reg', 'reg_bio', 'require_bio_reg'],
		},
		{
			key: 'registration_user_type',
			title: 'User Type Registration',
			fields: ['display_user_type'],
		},
		{
			key: 'registration_privacy_policy',
			title: 'Privacy Policy',
			fields: [
				'registration_privacy',
				'registration_privacy_label',
				'registration_privacy_label_link',
			],
		},
		{
			key: 'registration_terms_conditions',
			title: 'Terms Conditions',
			fields: [
				'regi_terms_condition',
				'regi_terms_label',
				'regi_terms_label_link',
			],
		},
		{
			key: 'registration_signup_button',
			title: 'Sign Up Button',
			fields: ['reg_signup'],
		},
		{
			key: 'registration_login_message',
			title: 'Login Message',
			fields: ['display_login', 'login_text', 'log_linkingmsg'],
		},
		{
			key: 'registration_redirect',
			title: 'Registration Redirect',
			fields: ['auto_login', 'redirection_after_reg'],
		},
	],

	userLogin: [
		{
			key: 'login_username',
			title: 'Username',
			fields: ['log_username'],
		},
		{
			key: 'login_password',
			title: 'Password',
			fields: ['log_password'],
		},
		{
			key: 'remember_login_info',
			title: 'Remember Login Information',
			fields: ['display_rememberme', 'log_rememberme'],
		},
		{
			key: 'login_button',
			title: 'Login Button',
			fields: ['log_button'],
		},
		{
			key: 'signup_message',
			title: 'Sign Up Message',
			fields: ['display_signup', 'reg_text', 'reg_linktxt'],
		},
		{
			key: 'recover_password',
			title: 'Recover Password',
			fields: [
				'display_recpass',
				'recpass_text',
				'recpass_desc',
				'recpass_username',
				'recpass_placeholder',
				'recpass_button',
			],
		},
		{
			key: 'login_redirect',
			title: 'Login Redirect',
			fields: ['redirection_after_login'],
		},
	],

	userDashboard: [
		{
			key: 'dashboard_tabs',
			title: 'Dashboard tabs',
			fields: [
				'my_profile_tab',
				'my_profile_tab_text',
				'fav_listings_tab',
				'fav_listings_tab_text',
				'my_listing_tab',
				'my_listing_tab_text',
			],
		},
		{
			key: 'dashboard_listings',
			title: 'Listings table',
			fields: [
				'user_listings_pagination',
				'user_listings_per_page',
				'submit_listing_button',
			],
		},
		{
			key: 'become_author',
			title: 'Become author',
			fields: ['become_author_button', 'become_author_button_text'],
		},
	],

	userAuthors: [
		{
			key: 'authors_layout',
			title: 'Author directory',
			fields: [
				'all_authors_columns',
				'all_authors_sorting',
				'all_authors_select_role',
			],
		},
		{
			key: 'authors_card',
			title: 'Author card',
			fields: [
				'all_authors_image',
				'all_authors_name',
				'all_authors_contact',
				'all_authors_description',
				'all_authors_social_info',
				'all_authors_description_limit',
				'all_authors_button',
				'all_authors_button_text',
			],
		},
		{
			key: 'authors_pagination',
			title: 'Pagination',
			fields: ['all_authors_pagination', 'all_authors_per_page'],
		},
	],

	monetizationCurrency: [
		{
			key: 'display_currency',
			title: 'Display currency',
			description:
				'How prices appear across listings, search, and the front-end.',
			fields: ['g_currency_note', 'g_currency', 'g_currency_position'],
			hiddenFields: ['g_currency_note'],
		},
		{
			key: 'checkout_currency',
			title: 'Checkout currency',
			description:
				'The currency customers are actually charged in when they pay.',
			fields: [
				'payment_currency_note',
				'payment_currency',
				'payment_currency_position',
				'payment_thousand_separator',
				'payment_decimal_separator',
			],
			hiddenFields: ['payment_currency_note'],
			checkoutCurrencyMatch: {
				beforeField: 'payment_currency',
				label: 'Match display currency',
				description:
					'Charge in the same currency you display. Turn off to settle payments in a different currency.',
			},
			advancedLabel: 'Number formatting',
			advancedFields: [
				'payment_thousand_separator',
				'payment_decimal_separator',
			],
		},
	],

	monetizationFeatured: [
		{
			key: 'featured_listings',
			title: 'Featured listings',
			fields: [
				'enable_monetization',
				'enable_featured_listing',
				'featured_listing_price',
				'featured_listing_time',
				'featured_listing_desc',
			],
		},
	],

	monetizationGateways: [
		{
			key: 'payment_gateways',
			title: 'Payment gateways',
			description:
				'Bank transfer is included. PayPal, Stripe, and Authorize.Net are available via Extensions.',
			fields: ['default_gateway'],
		},
		{
			key: 'payment_methods',
			title: 'Payment Methods',
			description:
				'Check the gateway(s) you would like to use to collect payment from your users. A user will be use any of the active gateways during the checkout process.',
			fields: ['active_gateways'],
		},
		{
			key: 'bank_transfer_details',
			title: 'Bank transfer details',
			fields: [
				'offline_payment_note',
				'bank_transfer_title',
				'bank_transfer_description',
				'bank_transfer_instruction',
			],
			hiddenFields: ['offline_payment_note'],
			advancedLabel: 'Description & bank instructions',
			advancedFields: [
				'bank_transfer_description',
				'bank_transfer_instruction',
			],
		},
	],

	monetizationBankTransfer: [
		{
			key: 'bank_transfer_details',
			title: 'Bank transfer details',
			fields: [
				'offline_payment_note',
				'bank_transfer_title',
				'bank_transfer_description',
				'bank_transfer_instruction',
			],
		},
	],

	notificationChannels: [
		{
			key: 'active_channels',
			title: 'Active channels',
			description: 'Turn each channel on or off across the directory.',
			fields: [
				'disable_email_notification',
				'web_push_notify_admin',
				'web_push_notify_user',
			],
			hiddenFields: ['web_push_notify_user'],
		},
		{
			key: 'web_push_setup',
			title: 'Web push setup',
			description: 'Connect this browser and review delivery logs.',
			fields: [
				'web_push_admin_subscription',
				'web_push_enable_log',
				'web_push_log_note',
			],
		},
		{
			key: 'sender_details',
			title: 'Sender details',
			fields: [
				'email_from_name',
				'email_from_email',
				'admin_email_lists',
			],
		},
		{
			key: 'email_template_styling',
			title: 'Email template styling',
			description: 'Applied to every outgoing email.',
			fields: ['allow_email_header', 'email_header_color'],
		},
	],

	notificationEvents: [
		{
			key: 'notification_events',
			title: 'Notification events',
			fields: ['notify_admin', 'notify_user'],
		},
		{
			key: 'schedule_timing',
			title: 'Schedule and timing',
			fields: ['email_to_expire_day', 'email_renewal_day'],
		},
	],

	notificationTemplates: [
		{
			key: 'listing_templates',
			title: 'Listing email templates',
			fields: [
				'email_note',
				'email_sub_new_listing',
				'email_tmpl_new_listing',
				'email_sub_pub_listing',
				'email_tmpl_pub_listing',
				'email_sub_rejected_listing',
				'email_tmpl_rejected_listing',
				'email_sub_edit_listing',
				'email_tmpl_edit_listing',
				'email_sub_to_expire_listing',
				'email_tmpl_to_expire_listing',
				'email_sub_expired_listing',
				'email_tmpl_expired_listing',
				'email_sub_to_renewal_listing',
				'email_tmpl_to_renewal_listing',
				'email_sub_renewed_listing',
				'email_tmpl_renewed_listing',
				'email_sub_deleted_listing',
				'email_tmpl_deleted_listing',
			],
		},
		{
			key: 'order_templates',
			title: 'Order email templates',
			fields: [
				'email_sub_new_order',
				'email_tmpl_new_order',
				'email_sub_offline_new_order',
				'email_tmpl_offline_new_order',
				'email_sub_completed_order',
				'email_tmpl_completed_order',
			],
		},
		{
			key: 'account_templates',
			title: 'Account emails',
			fields: [
				'email_sub_listing_contact_email',
				'email_tmpl_listing_contact_email',
				'email_sub_registration_confirmation',
				'email_tmpl_registration_confirmation',
				'email_sub_email_verification',
				'email_tmpl_email_verification',
			],
		},
	],

	notificationEventsTemplates: [
		{
			key: 'notification_events',
			title: 'Notification events',
			description:
				'Toggle a channel per event. Click Edit to customize subject, body, and push wording.',
			fields: [
				'notify_admin',
				'notify_user',
				'email_note',
				'email_sub_new_listing',
				'email_tmpl_new_listing',
				'email_sub_pub_listing',
				'email_tmpl_pub_listing',
				'email_sub_rejected_listing',
				'email_tmpl_rejected_listing',
				'email_sub_edit_listing',
				'email_tmpl_edit_listing',
				'email_sub_to_expire_listing',
				'email_tmpl_to_expire_listing',
				'email_sub_expired_listing',
				'email_tmpl_expired_listing',
				'email_sub_to_renewal_listing',
				'email_tmpl_to_renewal_listing',
				'email_sub_renewed_listing',
				'email_tmpl_renewed_listing',
				'email_sub_deleted_listing',
				'email_tmpl_deleted_listing',
				'email_sub_new_order',
				'email_tmpl_new_order',
				'email_sub_offline_new_order',
				'email_tmpl_offline_new_order',
				'email_sub_completed_order',
				'email_tmpl_completed_order',
				'email_sub_listing_contact_email',
				'email_tmpl_listing_contact_email',
				'email_sub_registration_confirmation',
				'email_tmpl_registration_confirmation',
				'email_sub_email_verification',
				'email_tmpl_email_verification',
				...WEB_PUSH_TEMPLATE_FIELDS,
				'email_to_expire_day',
				'email_renewal_day',
			],
			hiddenFields: [
				'notify_admin',
				'notify_user',
				'email_note',
				'email_sub_new_listing',
				'email_tmpl_new_listing',
				'email_sub_pub_listing',
				'email_tmpl_pub_listing',
				'email_sub_rejected_listing',
				'email_tmpl_rejected_listing',
				'email_sub_edit_listing',
				'email_tmpl_edit_listing',
				'email_sub_to_expire_listing',
				'email_tmpl_to_expire_listing',
				'email_sub_expired_listing',
				'email_tmpl_expired_listing',
				'email_sub_to_renewal_listing',
				'email_tmpl_to_renewal_listing',
				'email_sub_renewed_listing',
				'email_tmpl_renewed_listing',
				'email_sub_deleted_listing',
				'email_tmpl_deleted_listing',
				'email_sub_new_order',
				'email_tmpl_new_order',
				'email_sub_offline_new_order',
				'email_tmpl_offline_new_order',
				'email_sub_completed_order',
				'email_tmpl_completed_order',
				'email_sub_listing_contact_email',
				'email_tmpl_listing_contact_email',
				'email_sub_registration_confirmation',
				'email_tmpl_registration_confirmation',
				'email_sub_email_verification',
				'email_tmpl_email_verification',
				...WEB_PUSH_TEMPLATE_FIELDS,
			],
			notificationEvents: {
				beforeField: 'notify_admin',
			},
			advancedLabel: 'Schedule & timing',
			advancedFields: ['email_to_expire_day', 'email_renewal_day'],
		},
	],

	appearanceBrand: [
		{
			key: 'brand',
			title: 'Brand color',
			fields: ['brand_color'],
		},
		{
			key: 'buttons',
			title: 'Buttons',
			fields: [
				'button_type',
				'button_primary_example',
				'button_primary_color',
				'button_primary_bg_color',
				'button_secondary_example',
				'button_secondary_color',
				'button_secondary_bg_color',
			],
		},
		{
			key: 'map_markers',
			title: 'Map markers',
			fields: ['marker_shape_color', 'marker_icon_color'],
		},
	],

	appearanceBadges: [
		{
			key: 'badges_manager',
			title: '',
			description: '',
			fields: [
				'badge_display_type',
				'directorist_badge_rules',
				'new_badge_text',
				'new_listing_day',
				'new_back_color',
				'popular_badge_text',
				'listing_popular_by',
				'views_for_popular',
				'average_review_for_popular',
				'popular_back_color',
				'feature_badge_text',
				'featured_back_color',
			],
			hiddenFields: [
				'badge_display_type',
				'directorist_badge_rules',
				'new_badge_text',
				'new_listing_day',
				'new_back_color',
				'popular_badge_text',
				'listing_popular_by',
				'views_for_popular',
				'average_review_for_popular',
				'popular_back_color',
				'feature_badge_text',
				'featured_back_color',
			],
			badgesManager: {
				beforeField: 'badge_display_type',
			},
		},
	],

	sitePages: [
		{
			key: 'page_setup',
			title: 'Page setup',
			description:
				'Map each Directorist function to a WordPress page. These pages should contain the matching Directorist block.',
			fields: [
				'add_listing_page',
				'all_listing_page',
				'user_dashboard',
				'signin_signup_page',
				'author_profile_page',
				'all_categories_page',
				'single_category_page',
				'all_locations_page',
				'single_location_page',
				'single_tag_page',
				'search_listing',
				'search_result_page',
				'checkout_page',
				'payment_receipt_page',
				'transaction_failure_page',
				'privacy_policy',
				'terms_conditions',
			],
		},
		{
			key: 'regenerate_pages',
			title: 'Regenerate pages',
			fields: ['regenerate_pages'],
		},
	],

	siteSeo: [
		{
			key: 'seo_general',
			title: 'Built-in SEO',
			description:
				'Works alongside Yoast and other SEO plugins. Turn off if you want only your SEO plugin to control these pages.',
			fields: ['atbdp_enable_seo', 'meta_title_for_search_result'],
			hiddenFields: ['meta_title_for_search_result'],
		},
		{
			key: 'page_meta',
			title: 'Page titles and descriptions',
			description:
				'Meta title and description shown in search results for each Directorist page.',
			fields: [
				'all_listing_meta_title',
				'all_listing_meta_desc',
				'add_listing_page_meta_title',
				'add_listing_page_meta_desc',
				'dashboard_meta_title',
				'dashboard_meta_desc',
				'author_profile_meta_title',
				'author_page_meta_desc',
				'category_meta_title',
				'category_meta_desc',
				'single_category_meta_title',
				'single_category_meta_desc',
				'all_locations_meta_title',
				'all_locations_meta_desc',
				'single_locations_meta_title',
				'single_locations_meta_desc',
				'registration_meta_title',
				'registration_meta_desc',
				'login_meta_title',
				'login_meta_desc',
				'homepage_meta_title',
				'homepage_meta_desc',
				'search_result_meta_title',
				'search_result_meta_desc',
			],
			hiddenFields: [
				'all_listing_meta_title',
				'all_listing_meta_desc',
				'add_listing_page_meta_title',
				'add_listing_page_meta_desc',
				'dashboard_meta_title',
				'dashboard_meta_desc',
				'author_profile_meta_title',
				'author_page_meta_desc',
				'category_meta_title',
				'category_meta_desc',
				'single_category_meta_title',
				'single_category_meta_desc',
				'all_locations_meta_title',
				'all_locations_meta_desc',
				'single_locations_meta_title',
				'single_locations_meta_desc',
				'registration_meta_title',
				'registration_meta_desc',
				'login_meta_title',
				'login_meta_desc',
				'homepage_meta_title',
				'homepage_meta_desc',
				'search_result_meta_title',
				'search_result_meta_desc',
			],
			seoAdvancedLabel: 'Meta for the other pages',
			seoMetaPairs: [
				{
					key: 'all_listings',
					primary: true,
					titleLabel: 'All listings page meta title',
					titleField: 'all_listing_meta_title',
					descriptionLabel: 'All listings page meta description',
					descriptionField: 'all_listing_meta_desc',
				},
				{
					key: 'add_listing',
					titleLabel: 'Add listing page meta title',
					titleField: 'add_listing_page_meta_title',
					descriptionLabel: 'Add listing page meta description',
					descriptionField: 'add_listing_page_meta_desc',
				},
				{
					key: 'dashboard',
					titleLabel: 'Dashboard meta title',
					titleField: 'dashboard_meta_title',
					descriptionLabel: 'Dashboard meta description',
					descriptionField: 'dashboard_meta_desc',
				},
				{
					key: 'author_profile',
					titleLabel: 'Author profile meta title',
					titleField: 'author_profile_meta_title',
					descriptionLabel: 'Author profile meta description',
					descriptionField: 'author_page_meta_desc',
				},
				{
					key: 'categories',
					titleLabel: 'All categories page meta title',
					titleField: 'category_meta_title',
					descriptionLabel: 'All categories page meta description',
					descriptionField: 'category_meta_desc',
				},
				{
					key: 'single_category',
					titleLabel: 'Single category meta title',
					titleField: 'single_category_meta_title',
					descriptionLabel: 'Single category meta description',
					descriptionField: 'single_category_meta_desc',
				},
				{
					key: 'all_locations',
					titleLabel: 'All locations page meta title',
					titleField: 'all_locations_meta_title',
					descriptionLabel: 'All locations page meta description',
					descriptionField: 'all_locations_meta_desc',
				},
				{
					key: 'single_location',
					titleLabel: 'Single location meta title',
					titleField: 'single_locations_meta_title',
					descriptionLabel: 'Single location meta description',
					descriptionField: 'single_locations_meta_desc',
				},
				{
					key: 'search_home',
					titleLabel: 'Search page meta title',
					titleDescription: 'The search form / search home page.',
					titleField: 'homepage_meta_title',
					descriptionLabel: 'Search page meta description',
					descriptionField: 'homepage_meta_desc',
				},
				{
					key: 'search_result',
					titleLabel: 'Search results meta title',
					titleDescription: 'Used when search-friendly title is off.',
					titleField: 'search_result_meta_title',
					descriptionLabel: 'Search results meta description',
					descriptionField: 'search_result_meta_desc',
				},
				{
					key: 'account',
					titleLabel: 'Account page meta title',
					titleDescription: 'Single sign in / sign up page.',
					titleField: 'registration_meta_title',
					descriptionLabel: 'Account page meta description',
					descriptionField: 'registration_meta_desc',
				},
			],
		},
	],

	siteMaintenance: [
		{
			key: 'cache',
			title: 'Caching',
			fields: ['atbdp_enable_cache'],
		},
		{
			key: 'debugging',
			title: 'Developer tools',
			fields: ['script_debugging'],
		},
		{
			key: 'uninstall',
			title: 'Uninstall behavior',
			fields: ['enable_uninstall'],
		},
	],

	extensionsBrowse: [
		{
			key: 'installed_extensions',
			title: 'Extensions',
			fields: ['extension_promotion'],
		},
	],

	importExport: [
		{
			key: 'listings',
			title: 'Listings',
			description: 'Move listings as CSV with full custom field support.',
			fields: ['listing_import_button', 'listing_export_button'],
		},
		{
			key: 'settings',
			title: 'Settings',
			description: 'Move your configuration between Directorist sites.',
			fields: [
				'import_settings',
				'export_settings',
				'restore_default_settings',
			],
		},
	],
};

const mergeFieldOverride = (field, override) => ({
	...field,
	...override,
	componets: {
		...(field.componets || {}),
		...(override.componets || {}),
		link: {
			...(field.componets?.link || {}),
			...(override.componets?.link || {}),
		},
	},
});

export const applySettingsRedesignFieldOverrides = (fields = {}) => {
	const updatedFields = clone(fields);

	Object.keys(FIELD_OVERRIDES).forEach((fieldKey) => {
		if (!hasOwn(updatedFields, fieldKey)) {
			return;
		}

		updatedFields[fieldKey] = mergeFieldOverride(
			updatedFields[fieldKey],
			FIELD_OVERRIDES[fieldKey],
		);
	});

	return updatedFields;
};

const makeMenu = (rawMenu, label, options = {}) => ({
	label,
	icon: options.icon || rawMenu?.icon || '',
	...(options.sections ? { sections: options.sections } : {}),
	...(options.submenu ? { submenu: options.submenu } : {}),
});

const sectionFromFieldGroup = (group, fields, usedFields) => {
	const existingFields = group.fields.filter((fieldKey) =>
		hasOwn(fields, fieldKey),
	);

	if (!existingFields.length) {
		return null;
	}

	existingFields.forEach((fieldKey) => usedFields.add(fieldKey));

	return {
		title: group.title,
		description: group.description || '',
		...(group.advanced ? { advanced: true } : {}),
		...(group.advancedLabel ? { advancedLabel: group.advancedLabel } : {}),
		...(group.showIf ? { showIf: clone(group.showIf) } : {}),
		...(group.show_if ? { show_if: clone(group.show_if) } : {}),
		...(group['show-if'] ? { 'show-if': clone(group['show-if']) } : {}),
		...(group.defaultLocationAddress
			? { defaultLocationAddress: clone(group.defaultLocationAddress) }
			: {}),
		...(group.notificationEvents
			? { notificationEvents: clone(group.notificationEvents) }
			: {}),
		...(group.seoMetaPairs
			? { seoMetaPairs: clone(group.seoMetaPairs) }
			: {}),
		...(group.seoAdvancedLabel
			? { seoAdvancedLabel: group.seoAdvancedLabel }
			: {}),
		...(group.checkoutCurrencyMatch
			? { checkoutCurrencyMatch: clone(group.checkoutCurrencyMatch) }
			: {}),
		...(group.badgesManager
			? { badgesManager: clone(group.badgesManager) }
			: {}),
		...(Array.isArray(group.hiddenFields)
			? {
					hiddenFields: group.hiddenFields.filter((fieldKey) =>
						existingFields.includes(fieldKey),
					),
				}
			: {}),
		...(Array.isArray(group.advancedFields)
			? {
					advancedFields: group.advancedFields.filter((fieldKey) =>
						existingFields.includes(fieldKey),
					),
				}
			: {}),
		fields: existingFields,
	};
};

const sectionsFromGroups = (groups, fields, usedFields) => {
	const sections = {};

	groups.forEach((group) => {
		const section = sectionFromFieldGroup(group, fields, usedFields);

		if (section) {
			sections[group.key] = section;
		}
	});

	return sections;
};

const hasFieldsForGroups = (groups, fields) =>
	groups.some((group) =>
		group.fields.some((fieldKey) => hasOwn(fields, fieldKey)),
	);

const schemaGroups = (fields) => {
	const schemaFields = Object.keys(fields || {}).filter(
		(fieldKey) =>
			fieldKey.indexOf('directory_schema_type_') === 0 &&
			fieldKey !== 'directory_schema_type_global',
	);

	return [
		{
			key: 'schema_markup',
			title: 'Schema markup',
			description:
				'Adds structured data (JSON-LD) so your listings can appear with rich results in Google.',
			fields: [
				'enable_schema_markup',
				'apply_schema_markup',
				'directory_schema_type_global',
			],
		},
		...(schemaFields.length
			? [
					{
						key: 'schema_type_per_directory',
						title: 'Schema type per directory',
						description:
							"Choose a schema type for each directory you've built.",
						fields: schemaFields,
						showIf: [
							{
								where: 'enable_schema_markup',
								conditions: [
									{
										key: 'value',
										compare: '=',
										value: true,
									},
								],
							},
							{
								where: 'apply_schema_markup',
								conditions: [
									{
										key: 'value',
										compare: '=',
										value: 'per-directory',
									},
								],
							},
						],
					},
				]
			: []),
	];
};

const cloneRawMenu = (rawMenu, label, fields, usedFields) => {
	const menu = clone(rawMenu);
	menu.label = label || menu.label;

	const markSectionFields = (sections) => {
		Object.keys(sections || {}).forEach((sectionKey) => {
			sections[sectionKey].fields = (
				sections[sectionKey].fields || []
			).filter((fieldKey) => hasOwn(fields, fieldKey));
			sections[sectionKey].fields.forEach((fieldKey) =>
				usedFields.add(fieldKey),
			);
		});
	};

	if (menu.sections) {
		markSectionFields(menu.sections);
	}

	if (menu.submenu) {
		Object.keys(menu.submenu).forEach((submenuKey) => {
			markSectionFields(menu.submenu[submenuKey].sections || {});
		});
	}

	return menu;
};

const cloneRawSections = (
	sections = {},
	fields,
	usedFields,
	excludedFields = new Set(),
) => {
	const clonedSections = {};

	Object.keys(sections || {}).forEach((sectionKey) => {
		const section = clone(sections[sectionKey]);
		section.fields = (section.fields || [])
			.map(normalizeLayoutFieldKey)
			.filter(
				(fieldKey) =>
					fieldKey &&
					hasOwn(fields, fieldKey) &&
					!excludedFields.has(fieldKey),
			);

		if (!section.fields.length) {
			return;
		}

		section.fields.forEach((fieldKey) => usedFields.add(fieldKey));
		clonedSections[sectionKey] = section;
	});

	return clonedSections;
};

const REDESIGNED_MONETIZATION_SUBMENU_TARGETS = {
	monetization_general: 'monetization_general',
	monetization_submenu1: 'monetization_general',
	featured_listing: 'featured_listing',
	featured_listings: 'featured_listing',
	gateway: 'gateway',
	gateway_general: 'gateway',
	gateway_submenu: 'gateway',
	offline_gateway: 'gateway',
	offline_gateway_submenu: 'gateway',
	bank_transfer: 'gateway',
};

const buildMonetizationSettingsMenu = (rawMenu = {}, fields, usedFields) => {
	const submenu = {
		monetization_general: {
			label: 'Currency',
			icon: SETTINGS_REDESIGN_ICONS.currency,
			sections: sectionsFromGroups(
				FIELD_GROUPS.monetizationCurrency,
				fields,
				usedFields,
			),
		},
		featured_listing: {
			label: 'Featured listings',
			icon: SETTINGS_REDESIGN_ICONS.featured,
			sections: sectionsFromGroups(
				FIELD_GROUPS.monetizationFeatured,
				fields,
				usedFields,
			),
		},
		gateway: {
			label: 'Payment gateways',
			icon: SETTINGS_REDESIGN_ICONS.gateways,
			sections: sectionsFromGroups(
				FIELD_GROUPS.monetizationGateways,
				fields,
				usedFields,
			),
		},
	};
	const rawSubmenus = rawMenu?.submenu || {};

	Object.keys(rawSubmenus).forEach((submenuKey) => {
		if (REDESIGNED_MONETIZATION_SUBMENU_TARGETS[submenuKey]) {
			return;
		}

		const rawSubmenu = rawSubmenus[submenuKey] || {};
		const sections = cloneRawSections(
			rawSubmenu.sections || {},
			fields,
			usedFields,
			usedFields,
		);

		if (!Object.keys(sections).length) {
			return;
		}

		submenu[submenuKey] = {
			label: rawSubmenu.label || rawSubmenu.title || 'Payment gateway',
			icon: rawSubmenu.icon || SETTINGS_REDESIGN_ICONS.gateways,
			extensionSettings: true,
			sections,
		};
	});

	return makeMenu(rawMenu, 'Monetization', {
		icon: SETTINGS_REDESIGN_ICONS.monetization,
		submenu,
	});
};

const buildExtensionSettingsMenu = (rawLayouts, fields, usedFields) => {
	const rawMenu =
		rawLayouts.extension_settings || rawLayouts.extensions_settings || {};
	const rawSubmenus = rawMenu.submenu || {};
	const submenu = {};

	Object.keys(rawSubmenus).forEach((submenuKey) => {
		const rawSubmenu = rawSubmenus[submenuKey] || {};
		const sections =
			submenuKey === 'extensions_general'
				? {}
				: cloneRawSections(
						rawSubmenu.sections || {},
						fields,
						usedFields,
					);

		if (submenuKey === 'extensions_general') {
			const generalSections = sectionsFromGroups(
				FIELD_GROUPS.extensionsBrowse,
				fields,
				usedFields,
			);
			const extraGeneralSections = cloneRawSections(
				rawSubmenu.sections || {},
				fields,
				usedFields,
				new Set(['extension_promotion']),
			);

			Object.assign(sections, generalSections, extraGeneralSections);
		}

		if (!Object.keys(sections).length) {
			return;
		}

		submenu[submenuKey] = {
			label: rawSubmenu.label || 'Extension',
			icon: rawSubmenu.icon || SETTINGS_REDESIGN_ICONS.extensions,
			extensionSettings: submenuKey !== 'extensions_general',
			sections,
		};
	});

	if (
		!submenu.extensions_general &&
		hasFieldsForGroups(FIELD_GROUPS.extensionsBrowse, fields)
	) {
		submenu.extensions_general = {
			label: 'Extensions General',
			icon: SETTINGS_REDESIGN_ICONS.extensions,
			sections: sectionsFromGroups(
				FIELD_GROUPS.extensionsBrowse,
				fields,
				usedFields,
			),
		};
	}

	return makeMenu(rawMenu, 'Extensions', {
		icon: SETTINGS_REDESIGN_ICONS.extensions,
		submenu,
	});
};

const originalFieldPaths = (layouts, fields) => {
	const paths = [];

	Object.keys(layouts || {}).forEach((menuKey) => {
		const menu = layouts[menuKey];
		const readSections = (sections, submenuKey = '') => {
			Object.keys(sections || {}).forEach((sectionKey) => {
				(sections[sectionKey].fields || []).forEach((fieldKey) => {
					fieldKey = normalizeLayoutFieldKey(fieldKey);

					if (hasOwn(fields, fieldKey)) {
						paths.push({
							menuKey,
							submenuKey,
							sectionKey,
							fieldKey,
						});
					}
				});
			});
		};

		readSections(menu.sections || {});

		Object.keys(menu.submenu || {}).forEach((submenuKey) => {
			readSections(menu.submenu[submenuKey].sections || {}, submenuKey);
		});
	});

	return paths;
};

const appendUnmappedFields = (
	displayLayouts,
	rawLayouts,
	fields,
	usedFields,
) => {
	const designGapsMenuKey = 'settings_design_gaps';
	const submenuKeyForPath = (path) =>
		[path.menuKey, path.submenuKey || 'main']
			.filter(Boolean)
			.join('_')
			.replace(/[^a-zA-Z0-9_]/g, '_');

	displayLayouts[designGapsMenuKey] = makeMenu({}, 'Other', {
		icon: SETTINGS_REDESIGN_ICONS.help,
		submenu: {},
	});

	originalFieldPaths(rawLayouts, fields).forEach((path) => {
		if (
			usedFields.has(path.fieldKey) ||
			SUPPRESSED_REDESIGN_FIELDS.has(path.fieldKey)
		) {
			return;
		}

		const sourceMenu = rawLayouts[path.menuKey] || {};
		const sourceSubmenu = sourceMenu.submenu?.[path.submenuKey] || {};
		const sourceSection =
			(path.submenuKey
				? sourceSubmenu.sections?.[path.sectionKey]
				: sourceMenu.sections?.[path.sectionKey]) || {};
		const gapSubmenuKey = submenuKeyForPath(path);
		const gapSubmenuLabel = sourceSubmenu.label
			? `${sourceMenu.label || 'Settings'} / ${sourceSubmenu.label}`
			: sourceMenu.label || 'Settings';
		const sectionKey = [
			'needs_design',
			path.menuKey,
			path.submenuKey || 'main',
			path.sectionKey,
		].join('_');

		if (!displayLayouts[designGapsMenuKey].submenu[gapSubmenuKey]) {
			displayLayouts[designGapsMenuKey].submenu[gapSubmenuKey] = {
				label: gapSubmenuLabel,
				sections: {},
			};
		}

		const targetSubmenu =
			displayLayouts[designGapsMenuKey].submenu[gapSubmenuKey];

		if (!targetSubmenu.sections[sectionKey]) {
			targetSubmenu.sections[sectionKey] = {
				title:
					sourceSection.title ||
					sourceSubmenu.label ||
					sourceMenu.label ||
					'Needs design',
				description:
					sourceSection.description ||
					'This setting exists in the current panel but was not represented in the static redesign mockup.',
				fields: [],
			};
		}

		targetSubmenu.sections[sectionKey].fields.push(path.fieldKey);
		usedFields.add(path.fieldKey);
	});
};

const getRecognizedFallbackTarget = (path) => {
	const menuKey = path.menuKey || '';
	const submenuKey = path.submenuKey || '';

	if (['page_settings', 'page_setup'].includes(menuKey)) {
		return {
			menuKey: 'page_setup',
			submenuKey: 'pages',
		};
	}

	if (menuKey === 'email_settings' && submenuKey === 'email_templates') {
		return {
			menuKey: 'email_settings',
			submenuKey: 'email_events',
		};
	}

	if (
		['extension_settings', 'extensions_settings'].includes(menuKey) &&
		submenuKey === 'extensions_general'
	) {
		return {
			menuKey: 'extension_settings',
			submenuKey: 'extensions_general',
		};
	}

	if (
		['extension_settings', 'extensions_settings'].includes(menuKey) &&
		submenuKey
	) {
		return {
			menuKey: 'extension_settings',
			submenuKey,
		};
	}

	if (
		menuKey === 'monetization_settings' &&
		REDESIGNED_MONETIZATION_SUBMENU_TARGETS[submenuKey]
	) {
		return {
			menuKey: 'monetization_settings',
			submenuKey: REDESIGNED_MONETIZATION_SUBMENU_TARGETS[submenuKey],
		};
	}

	return null;
};

const appendFieldsToExistingTab = (
	displayLayouts,
	rawLayouts,
	fields,
	usedFields,
) => {
	originalFieldPaths(rawLayouts, fields).forEach((path) => {
		if (
			usedFields.has(path.fieldKey) ||
			SUPPRESSED_REDESIGN_FIELDS.has(path.fieldKey)
		) {
			return;
		}

		const target = getRecognizedFallbackTarget(path);

		if (!target) {
			return;
		}

		const targetMenu = displayLayouts[target.menuKey];
		const targetSubmenu = targetMenu?.submenu?.[target.submenuKey];

		if (!targetMenu || !targetSubmenu) {
			return;
		}

		if (!targetSubmenu.sections) {
			targetSubmenu.sections = {};
		}

		const sourceMenu = rawLayouts[path.menuKey] || {};
		const sourceSubmenu = sourceMenu.submenu?.[path.submenuKey] || {};
		const sourceSection =
			(path.submenuKey
				? sourceSubmenu.sections?.[path.sectionKey]
				: sourceMenu.sections?.[path.sectionKey]) || {};
		const sectionKey = [
			'routed',
			path.menuKey,
			path.submenuKey || 'main',
			path.sectionKey,
		].join('_');

		if (!targetSubmenu.sections[sectionKey]) {
			targetSubmenu.sections[sectionKey] = {
				title:
					sourceSection.title ||
					sourceSubmenu.label ||
					sourceMenu.label ||
					targetSubmenu.label,
				description: sourceSection.description || '',
				fields: [],
			};
		}

		targetSubmenu.sections[sectionKey].fields.push(path.fieldKey);
		usedFields.add(path.fieldKey);
	});
};

const pruneEmptyNavigation = (displayLayouts) => {
	Object.keys(displayLayouts).forEach((menuKey) => {
		const menu = displayLayouts[menuKey];

		if (menu.sections) {
			Object.keys(menu.sections).forEach((sectionKey) => {
				if (!(menu.sections[sectionKey].fields || []).length) {
					delete menu.sections[sectionKey];
				}
			});
		}

		if (menu.submenu) {
			Object.keys(menu.submenu).forEach((submenuKey) => {
				const submenu = menu.submenu[submenuKey];

				Object.keys(submenu.sections || {}).forEach((sectionKey) => {
					if (!(submenu.sections[sectionKey].fields || []).length) {
						delete submenu.sections[sectionKey];
					}
				});

				if (!Object.keys(submenu.sections || {}).length) {
					delete menu.submenu[submenuKey];
				}
			});

			if (!Object.keys(menu.submenu).length) {
				delete displayLayouts[menuKey];
			}
		}

		if (menu.sections && !Object.keys(menu.sections).length) {
			delete displayLayouts[menuKey];
		}
	});
};

export const buildSettingsRedesignLayout = (rawLayouts = {}, fields = {}) => {
	const usedFields = new Set();
	const displayLayouts = {};

	displayLayouts.listing_settings = makeMenu(
		rawLayouts.listing_settings,
		'Directory',
		{
			icon: SETTINGS_REDESIGN_ICONS.directory,
			submenu: {
				general: {
					label: 'General',
					icon: SETTINGS_REDESIGN_ICONS.general,
					sections: sectionsFromGroups(
						FIELD_GROUPS.directoriesGeneral,
						fields,
						usedFields,
					),
				},
				listings_page: {
					label: 'Listings page',
					icon: SETTINGS_REDESIGN_ICONS.listings,
					sections: sectionsFromGroups(
						FIELD_GROUPS.directoriesListingsPage,
						fields,
						usedFields,
					),
				},
				single_listing: {
					label: 'Single listing',
					icon: SETTINGS_REDESIGN_ICONS.singleListing,
					sections: sectionsFromGroups(
						FIELD_GROUPS.directoriesSingleListing,
						fields,
						usedFields,
					),
				},
				submissions: {
					label: 'Submissions',
					icon: SETTINGS_REDESIGN_ICONS.submissions,
					sections: sectionsFromGroups(
						FIELD_GROUPS.directoriesSubmissions,
						fields,
						usedFields,
					),
				},
				category_location: {
					label: 'Categories & locations',
					icon: SETTINGS_REDESIGN_ICONS.taxonomies,
					sections: sectionsFromGroups(
						FIELD_GROUPS.directoriesCategoryLocation,
						fields,
						usedFields,
					),
				},
				map: {
					label: 'Map',
					icon: SETTINGS_REDESIGN_ICONS.map,
					sections: sectionsFromGroups(
						FIELD_GROUPS.directoriesMap,
						fields,
						usedFields,
					),
				},
				review: {
					label: 'Reviews',
					icon: SETTINGS_REDESIGN_ICONS.reviews,
					sections: sectionsFromGroups(
						FIELD_GROUPS.directoriesReviews,
						fields,
						usedFields,
					),
				},
			},
		},
	);

	displayLayouts.search_settings = makeMenu(
		rawLayouts.search_settings,
		'Search',
		{
			icon: SETTINGS_REDESIGN_ICONS.search,
			submenu: {
				search_listing: {
					label: 'Search form',
					icon: SETTINGS_REDESIGN_ICONS.search,
					sections: sectionsFromGroups(
						FIELD_GROUPS.searchForm,
						fields,
						usedFields,
					),
				},
				search_result: {
					label: 'Search results',
					icon: SETTINGS_REDESIGN_ICONS.listings,
					sections: sectionsFromGroups(
						FIELD_GROUPS.searchResults,
						fields,
						usedFields,
					),
				},
			},
		},
	);

	displayLayouts.user_settings = makeMenu(
		rawLayouts.user_settings,
		'Users & accounts',
		{
			icon: SETTINGS_REDESIGN_ICONS.users,
			submenu: {
				registration_form: {
					label: 'Registration',
					icon: SETTINGS_REDESIGN_ICONS.users,
					sections: sectionsFromGroups(
						FIELD_GROUPS.userRegistration,
						fields,
						usedFields,
					),
				},
				login_form: {
					label: 'Login',
					icon: SETTINGS_REDESIGN_ICONS.singleListing,
					sections: sectionsFromGroups(
						FIELD_GROUPS.userLogin,
						fields,
						usedFields,
					),
				},
				dashboard: {
					label: 'Dashboard',
					icon: SETTINGS_REDESIGN_ICONS.pages,
					sections: sectionsFromGroups(
						FIELD_GROUPS.userDashboard,
						fields,
						usedFields,
					),
				},
				all_authors: {
					label: 'Authors',
					icon: SETTINGS_REDESIGN_ICONS.users,
					sections: sectionsFromGroups(
						FIELD_GROUPS.userAuthors,
						fields,
						usedFields,
					),
				},
			},
		},
	);

	displayLayouts.monetization_settings = buildMonetizationSettingsMenu(
		rawLayouts.monetization_settings,
		fields,
		usedFields,
	);

	displayLayouts.email_settings = makeMenu(
		rawLayouts.email_settings,
		'Notifications',
		{
			icon: SETTINGS_REDESIGN_ICONS.notifications,
			submenu: {
				email_general: {
					label: 'Channels',
					icon: SETTINGS_REDESIGN_ICONS.channels,
					sections: sectionsFromGroups(
						FIELD_GROUPS.notificationChannels,
						fields,
						usedFields,
					),
				},
				email_events: {
					label: 'Events & Templates',
					icon: SETTINGS_REDESIGN_ICONS.events,
					sections: sectionsFromGroups(
						FIELD_GROUPS.notificationEventsTemplates,
						fields,
						usedFields,
					),
				},
			},
		},
	);

	displayLayouts.style_settings = makeMenu(
		rawLayouts.style_settings,
		'Appearance',
		{
			icon: SETTINGS_REDESIGN_ICONS.appearance,
			submenu: {
				brand_styling: {
					label: 'Brand & styling',
					icon: SETTINGS_REDESIGN_ICONS.brand,
					sections: sectionsFromGroups(
						FIELD_GROUPS.appearanceBrand,
						fields,
						usedFields,
					),
				},
				badges: {
					label: 'Badges',
					icon: SETTINGS_REDESIGN_ICONS.badges,
					sections: sectionsFromGroups(
						FIELD_GROUPS.appearanceBadges,
						fields,
						usedFields,
					),
				},
			},
		},
	);

	displayLayouts.page_setup = makeMenu(
		rawLayouts.page_setup,
		'Site & pages',
		{
			icon: SETTINGS_REDESIGN_ICONS.sitePages,
			submenu: {
				pages: {
					label: 'Pages',
					icon: SETTINGS_REDESIGN_ICONS.pages,
					sections: sectionsFromGroups(
						FIELD_GROUPS.sitePages,
						fields,
						usedFields,
					),
				},
				seo_settings: {
					label: 'SEO',
					icon: SETTINGS_REDESIGN_ICONS.seo,
					sections: sectionsFromGroups(
						FIELD_GROUPS.siteSeo,
						fields,
						usedFields,
					),
				},
				schema_markup: {
					label: 'Schema',
					icon: SETTINGS_REDESIGN_ICONS.schema,
					sections: sectionsFromGroups(
						schemaGroups(fields),
						fields,
						usedFields,
					),
				},
				maintenance: {
					label: 'Maintenance',
					icon: SETTINGS_REDESIGN_ICONS.maintenance,
					sections: sectionsFromGroups(
						FIELD_GROUPS.siteMaintenance,
						fields,
						usedFields,
					),
				},
			},
		},
	);

	if (
		rawLayouts.extension_settings ||
		rawLayouts.extensions_settings ||
		hasFieldsForGroups(FIELD_GROUPS.extensionsBrowse, fields)
	) {
		displayLayouts.extension_settings = buildExtensionSettingsMenu(
			rawLayouts,
			fields,
			usedFields,
		);
	}

	if (
		rawLayouts.tools ||
		hasFieldsForGroups(FIELD_GROUPS.importExport, fields)
	) {
		displayLayouts.tools = makeMenu(rawLayouts.tools, 'Import / Export', {
			icon: SETTINGS_REDESIGN_ICONS.tools,
			sections: sectionsFromGroups(
				FIELD_GROUPS.importExport,
				fields,
				usedFields,
			),
		});
	}

	appendFieldsToExistingTab(displayLayouts, rawLayouts, fields, usedFields);
	appendUnmappedFields(displayLayouts, rawLayouts, fields, usedFields);
	pruneEmptyNavigation(displayLayouts);

	return displayLayouts;
};

export const resolveSettingsHashTarget = (
	hash,
	layouts = {},
	cachedFields = {},
) => {
	const cleanHash = String(hash || '').replace(/#/g, '');

	if (!cleanHash) {
		return null;
	}

	const parts = cleanHash.split('__');
	const fieldKey = parts[parts.length - 1];

	if (cachedFields[fieldKey]?.layout_path) {
		return cachedFields[fieldKey].layout_path;
	}

	const extensionCompatibleHash =
		cleanHash.indexOf('extensions_settings__') === 0
			? cleanHash.replace(/^extensions_settings/, 'extension_settings')
			: cleanHash;

	const directAliases = {
		search_settings: 'search_settings__search_listing',
		search_settings__search_listing: 'search_settings__search_listing',
		search_settings__search_result: 'search_settings__search_result',
		user_settings: 'user_settings__registration_form',
		user_settings__registration_form: 'user_settings__registration_form',
		user_settings__login_form: 'user_settings__login_form',
		user_settings__dashboard: 'user_settings__dashboard',
		user_settings__all_authors: 'user_settings__all_authors',
		listing_settings__listings_page: 'listing_settings__listings_page',
		listing_settings__category_location:
			'listing_settings__category_location',
		page_setup: 'page_setup__pages',
		page_settings: 'page_setup__pages',
		page_settings__upgrade_pages: 'page_setup__pages',
		page_settings__pages_links_views: 'page_setup__pages',
		advanced: 'page_setup__seo_settings',
		advanced__seo_settings: 'page_setup__seo_settings',
		advanced__schema_markup: 'page_setup__schema_markup',
		advanced__miscellaneous: 'page_setup__maintenance',
		style_settings: 'style_settings__brand_styling',
		listing_settings__badge: 'style_settings__badges',
		listing_settings__review: 'listing_settings__review',
		email_settings__email_templates: 'email_settings__email_events',
		email_settings__email_events_templates: 'email_settings__email_events',
		extensions_settings: 'extension_settings__extensions_general',
		extension_settings: 'extension_settings__extensions_general',
		extensions_settings__extensions_general:
			'extension_settings__extensions_general',
		extension_settings__extensions_general:
			'extension_settings__extensions_general',
	};

	const resolvedHash =
		directAliases[extensionCompatibleHash] || extensionCompatibleHash;
	const resolvedParts = resolvedHash.split('__');
	const menuKey = resolvedParts[0];
	const submenuKey = resolvedParts.length > 1 ? resolvedParts[1] : '';

	if (!layouts[menuKey]) {
		return null;
	}

	if (submenuKey && !layouts[menuKey].submenu?.[submenuKey]) {
		return null;
	}

	return {
		menu_key: menuKey,
		submenu_key: submenuKey,
		hash: resolvedHash,
	};
};
