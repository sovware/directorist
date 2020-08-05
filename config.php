<?php
// Plugin version.
if ( ! defined( 'ATBDP_VERSION' ) ) {define( 'ATBDP_VERSION', '6.5.1' );}
// Plugin Folder Path.
if ( ! defined( 'ATBDP_DIR' ) ) { define( 'ATBDP_DIR', plugin_dir_path( __FILE__ ) ); }
// Plugin Folder URL.
if ( ! defined( 'ATBDP_URL' ) ) { define( 'ATBDP_URL', plugin_dir_url( __FILE__ ) ); }
// Plugin Root File.
if ( ! defined( 'ATBDP_FILE' ) ) { define( 'ATBDP_FILE', __FILE__ ); }
if ( ! defined( 'ATBDP_BASE' ) ) { define( 'ATBDP_BASE', plugin_basename( __FILE__ ) ); }
// Plugin Text domain File.
if ( ! defined( 'ATBDP_TEXTDOMAIN' ) ) { define( 'ATBDP_TEXTDOMAIN' , 'directorist' ); }
// Plugin Includes Path
if ( !defined('ATBDP_INC_DIR') ) { define('ATBDP_INC_DIR', ATBDP_DIR.'includes/'); }
// Plugin Class Path
if ( !defined('ATBDP_CLASS_DIR') ) { define('ATBDP_CLASS_DIR', ATBDP_DIR.'includes/classes/'); }
// Plugin Model Path
if ( !defined('ATBDP_MODEL_DIR') ) { define('ATBDP_MODEL_DIR', ATBDP_DIR.'includes/model/'); }
// Plugin Library Path
if ( !defined('ATBDP_LIB_DIR') ) { define('ATBDP_LIB_DIR', ATBDP_DIR.'includes/libs/'); }
// Plugin Template Path
if ( !defined('ATBDP_TEMPLATES_DIR') ) { define('ATBDP_TEMPLATES_DIR', ATBDP_DIR.'templates/'); }

if ( !defined('ATBDP_SHORTCODE_TEMPLATES_THEME_DIR') ) { define( 'ATBDP_SHORTCODE_TEMPLATES_THEME_DIR', '/directorist/shortcodes/' ); }
if ( !defined('ATBDP_SHORTCODE_TEMPLATES_DEFAULT_DIR') ) { define( 'ATBDP_SHORTCODE_TEMPLATES_DEFAULT_DIR', ATBDP_TEMPLATES_DIR . "public-templates/shortcodes/" ); }

if ( !defined('ATBDP_WIDGET_TEMPLATES_THEME_DIR') ) { define( 'ATBDP_WIDGET_TEMPLATES_THEME_DIR', '/directorist/widgets/' ); }
if ( !defined('ATBDP_WIDGET_TEMPLATES_DEFAULT_DIR') ) { define( 'ATBDP_WIDGET_TEMPLATES_DEFAULT_DIR', ATBDP_TEMPLATES_DIR . "public-templates/widgets/" ); }

// Plugin Admin Assets Path
if ( !defined('ATBDP_ADMIN_ASSETS') ) { define('ATBDP_ADMIN_ASSETS', ATBDP_URL.'admin/assets/'); }
// Plugin Public Assets Path
if ( !defined('ATBDP_PUBLIC_ASSETS') ) { define('ATBDP_PUBLIC_ASSETS', ATBDP_URL.'public/assets/'); }
// Plugin Language File Path
if ( !defined('ATBDP_LANG_DIR') ) { define('ATBDP_LANG_DIR', dirname(plugin_basename( __FILE__ ) ) . '/languages'); }
// Plugin Name
if ( !defined('ATBDP_NAME') ) { define('ATBDP_NAME', 'Directorist'); }
// Plugin Post Type
if ( !defined('ATBDP_POST_TYPE') ) { define('ATBDP_POST_TYPE', 'at_biz_dir'); }
if ( !defined('ATBDP_ORDER_POST_TYPE') ) { define('ATBDP_ORDER_POST_TYPE', 'atbdp_orders'); }
if ( !defined('ATBDP_CUSTOM_FIELD_POST_TYPE') ) { define('ATBDP_CUSTOM_FIELD_POST_TYPE', 'atbdp_fields'); }
// Plugin Category Taxonomy
if ( !defined('ATBDP_CATEGORY') ) { define('ATBDP_CATEGORY', ATBDP_POST_TYPE.'-category'); }
// Plugin Location Taxonomy
if ( !defined('ATBDP_LOCATION') ) { define('ATBDP_LOCATION', ATBDP_POST_TYPE.'-location'); }
// Plugin Location Taxonomy
if ( !defined('ATBDP_TAGS') ) { define('ATBDP_TAGS', ATBDP_POST_TYPE.'-tags'); }

// Plugin Alert Message
if ( !defined('ATBDP_ALERT_MSG') ) { define('ATBDP_ALERT_MSG', __('You do not have the right to access this file directly', 'directorist')); }


// Plugin Veiw Path
if ( !defined('ATBDP_VIEW_DIR') ) { define('ATBDP_VIEW_DIR', ATBDP_DIR.'includes/view/'); }