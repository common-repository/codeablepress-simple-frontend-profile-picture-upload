<?php defined( 'ABSPATH' ) || exit;

/**
 * Includes necessary files and registers plugin hooks.
 */
$files = [
    'includes/init.php',                   // Handles code that runs on plugin install or page load.
    'includes/utilities.php',              // Provides utility functions used across the plugin.
    'includes/enqueue.php',                // Handles script and style enqueuing.
    'includes/filters.php',                // Applies filters for avatar customization.
    'includes/template-loaders.php',        // Handles the loading and rendering of template files.
    'includes/shortcodes.php',             // Manages shortcodes for displaying custom avatars and user information.
    'includes/upload.php',                 // Contains functions for uploading avatar.
];

if (is_admin()) {
    $files[] = 'admin/profile.php';        // Contains the avatar delete function in User Profile pages.
}

foreach ($files as $file) {
    require_once CSFPP_PLUGIN_DIR . $file;
}

// Frontend and common hooks
add_action('plugins_loaded', 'csfpp_load_textdomain');

// Display avatar
add_action('plugins_loaded', function() {
    if ( class_exists( 'WooCommerce' ) ) {
        add_action('woocommerce_before_account_navigation', function() {
            echo do_shortcode('[csfpp_avatar type="1"]');
        });
    }
});