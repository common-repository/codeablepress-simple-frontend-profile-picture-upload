<?php defined( 'ABSPATH' ) || exit;

/**
 * Enqueues frontend scripts and styles. Called by the csfpp_load_avatar_template().
 *
 * @since 1.0.0
 */
function csfpp_enqueue_styles_and_scripts() {
    wp_enqueue_style('csfpp-style', CSFPP_PLUGIN_URL . 'assets/csfpp.css', array(), '3.0.0');

    wp_enqueue_style('csfpp-tailwind-style', CSFPP_PLUGIN_URL . 'lib/tailwind.css', array(), '3.0.0');

    if (!is_user_logged_in() || (is_author() && get_the_author_meta('ID') !== get_current_user_id())) return;

    wp_enqueue_script('smartcrop', CSFPP_PLUGIN_URL . 'lib/smartcrop.js', array('jquery'), '2.0.5', true);

    wp_enqueue_script('csfpp-script', CSFPP_PLUGIN_URL . 'assets/csfpp.js', array('jquery', 'smartcrop'), '3.0.0', true);
    wp_localize_script('csfpp-script', 'csfpp_avatar_upload_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('csfpp_profile_picture_upload_nonce'),
    ));
}

/**
 * Enqueues admin-specific scripts and styles.
 *
 * @since 1.0.0
 * 
 * @param string $hook_suffix The current admin page's hook suffix.
 */
add_action('admin_enqueue_scripts', function($hook_suffix) {
    // Ensure scripts are only added to the user profile or user edit pages.
    if ($hook_suffix !== 'profile.php' && $hook_suffix !== 'user-edit.php') return;

    global $user_id; // Using WordPress core's global $user_id to access the current profile page user's ID within the admin.
    // TODO: Use or remove this line pending Wordpress feedback
    // $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : get_current_user_id();
    
    wp_enqueue_media();

    wp_enqueue_script('csfpp-admin-script', CSFPP_PLUGIN_URL . 'assets/csfpp-admin.js', array('jquery'), '3.0.0', true);
    wp_localize_script('csfpp-admin-script', 'csfpp_admin_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('csfpp_delete_profile_picture_nonce'),
        'user_id' => $user_id
    ));
});