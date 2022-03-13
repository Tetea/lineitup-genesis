<?php

// Child Theme Info
define('CHILD_THEME_NAME', 'Line It Up 2');
define('CHILD_THEME_VERSION', '2.0.4');
define('CHILD_THEME_SETTINGS', 'lineitup-settings');

// Dirs and Urls
define('CHILD_THEME_LIB_DIR', CHILD_DIR.'/lib');
define('CHILD_THEME_LIB_URL', CHILD_URL.'/lib');
define('STYLES_URL', CHILD_URL.'/styles');

// Add Options
define('LINEITUP_SETTINGS_FIELD', apply_filters('themedy_settings_field', CHILD_THEME_SETTINGS));
require_once(CHILD_THEME_LIB_DIR.'/admin/themedy-options.php');

// Add Settings pages
require_once(CHILD_THEME_LIB_DIR.'/admin/themedy-settings.php');
require_once(CHILD_THEME_LIB_DIR.'/admin/lineitup-settings.php');

// Add Customizer options
require_once(CHILD_THEME_LIB_DIR.'/admin/themedy-customizer.php');

// Add Plugins
require_once(CHILD_THEME_LIB_DIR.'/plugins/plugins.php'); // TGM Framework https://github.com/thomasgriffin/TGM-Plugin-Activation

// Localization
load_child_theme_textdomain( 'themedy', CHILD_THEME_LIB_DIR . '/languages');

// Portfolio Functionality
include(CHILD_THEME_LIB_DIR.'/functions/themedy-portfolio.php');

// Other custom post types
include(CHILD_THEME_LIB_DIR.'/functions/themedy-cpt.php');

// Slides Functionality
include(CHILD_THEME_LIB_DIR.'/functions/themedy-slides.php');

// Add Meta Boxes
require_once(CHILD_THEME_LIB_DIR.'/functions/themedy-metaboxes.php');

// WooCommerce Functionality (http://wordpress.org/plugins/woocommerce/)
include(CHILD_THEME_LIB_DIR.'/functions/themedy-woocommerce.php');