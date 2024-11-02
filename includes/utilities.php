<?php defined( 'ABSPATH' ) || exit;

/**
 * Determines the user id as the currenter user id or the page author id.
 *
 * @since 1.0.0
 * @param bool $check_page_author Whether to determine the user ID based on the type of page being viewed.
 * @return int The determined user ID.
 */
function csfpp_get_page_author_id($check_page_author) {
    if ($check_page_author && (is_author() || is_singular())) {
        return is_author() ? absint(get_queried_object_id()) : absint(get_post_field('post_author', get_the_ID()));
    } else {
        return absint(get_current_user_id());
    }
}

/**
 * Retrieves the username based on user ID.
 *
 * @since 1.0.0
 * @param int|null $user_id The ID of the user.
 * @return string The username or 'Guest' if user ID is not valid.
 */
function csfpp_get_name($user_id) {
    $avatar_owner = get_userdata($user_id);
    if ($avatar_owner) {
        return wp_kses_post($avatar_owner->display_name ?: $avatar_owner->first_name);
    }
    return esc_html__('Guest', 'codeablepress-simple-frontend-profile-picture-upload');
}

/**
 * Get the thumbnail size, with optional overrides for width and height.
 *
 * @since 1.0.0
 * @param int|null $width  Optional. Custom width to override the default thumbnail width.
 * @param int|null $height Optional. Custom height to override the default thumbnail height.
 * @return array The width and height for the thumbnail size.
 */
function csfpp_get_upload_size($width = null, $height = null) {
    $thumbnail_width = $width ?: get_option('thumbnail_size_w');
    $thumbnail_height = $height ?: get_option('thumbnail_size_h');
    
    return [$thumbnail_width, $thumbnail_height];
}

/**
 * Delete the user's old avatar from the media library.
 *
 * @since 1.0.0
 * @param int $user_id The ID of the user whose avatar should be deleted.
 */
function csfpp_delete_users_old_avatar($user_id) {
    $avatar_id = get_user_meta($user_id, 'csfpp_avatar', true);
    if (!empty($avatar_id)) {
        wp_delete_attachment($avatar_id, true);
    }
}