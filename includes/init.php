<?php defined( 'ABSPATH' ) || exit;

/**
 * Load the plugin text domain for translation.
 * 
 * @since 1.0.0
 */
function csfpp_load_textdomain() {
    load_plugin_textdomain('csfpp', false, CSFPP_PLUGIN_DIR . 'languages');
}