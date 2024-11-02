<?php 
defined( 'ABSPATH' ) || exit;
if (!is_admin()) return;

/**
 * Adds a delete button for the custom avatar on the user profile page in the admin area.
 *
 * @since 1.0.0
 *
 * @param array $args The arguments passed to the action.
 */
add_action('user_profile_picture_description', function($args) {
    global $user_id; // Using WordPress core's global $user_id to access the current profile page user's ID within the admin.
    // TODO: Use or remove this line pending Wordpress feedback
    // $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : get_current_user_id();
    
    $custom_avatar_id = get_user_meta($user_id, 'csfpp_avatar', true);
    $nonce = wp_create_nonce('csfpp_save_profile_picture_nonce');
    $content = '<input type="hidden" name="profile_picture" id="profile_picture" value="' . esc_attr($custom_avatar_id) . '">';
    $content .= '<input type="hidden" name="csfpp_profile_picture_nonce" value="' . esc_attr($nonce) . '">';

    if ($custom_avatar_id && current_user_can('edit_user', $user_id)) {
        $delete_nonce = wp_create_nonce('csfpp_delete_profile_picture_nonce');
        $content .= '<button type="button" id="delete-profile-picture" class="button" data-nonce="' . esc_attr($delete_nonce) . '">Delete Custom Avatar</button>';
    }

    $allowed_tags = array(
        'input' => array(
            'type' => array(),
            'name' => array(),
            'id' => array(),
            'value' => array(),
            'class' => array(),
            'data-nonce' => array(),
        ),
        'button' => array(
            'type' => array(),
            'id' => array(),
            'class' => array(),
            'data-nonce' => array(),
        ),
        'pre' => array(),
    );

    echo wp_kses($content, $allowed_tags);

    // Display the default content
    echo '<pre>' . wp_kses_post($args) . '</pre>';
});


/**
 * Handles the deletion of a custom avatar via an AJAX request.
 *
 * @since 1.0.0
 */
add_action('wp_ajax_csfpp_delete_profile_picture', function() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'csfpp_delete_profile_picture_nonce')) {
        wp_send_json_error('Nonce is not valid.');
    }

    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    if (!isset($_POST['user_id']) || !($user_id = intval(wp_unslash($_POST['user_id'])))) {
        wp_send_json_error('User ID is not valid.');
    }

    csfpp_delete_users_old_avatar($user_id);
    delete_user_meta($user_id, 'csfpp_avatar');

    wp_send_json_success('Profile picture deleted successfully.');
});

/**
 * Save custom user profile picture when user profile is updated.
 *
 * @param int $user_id The ID of the user whose profile is being updated.
 * @since 1.0.0
 */
add_action('profile_update', function($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (isset($_POST['profile_picture'])) {
        $nonce = isset($_POST['csfpp_profile_picture_nonce']) ? sanitize_text_field(wp_unslash($_POST['csfpp_profile_picture_nonce'])) : '';
        if (!$nonce || !wp_verify_nonce($nonce, 'csfpp_save_profile_picture_nonce')) {
            return false;
        }

        $new_avatar_id = sanitize_text_field(wp_unslash($_POST['profile_picture']));
        $current_avatar_id = get_user_meta($user_id, 'csfpp_avatar', true);

        // Only delete the old avatar if a new one is being set and it's different from the current one
        if ($new_avatar_id !== $current_avatar_id) {
            csfpp_delete_users_old_avatar($user_id);
            update_user_meta($user_id, 'csfpp_avatar', $new_avatar_id);
        }
    }
});
