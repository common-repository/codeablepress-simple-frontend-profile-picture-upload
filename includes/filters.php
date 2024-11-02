<?php defined( 'ABSPATH' ) || exit;

/**
 * Filters the avatar URL to use the custom uploaded avatar.
 *
 * @since 1.0.0
 * @param string $url         The URL of the avatar.
 * @param mixed  $id_or_email The user ID or email address.
 * @param array  $args        Additional arguments.
 * @return string The URL of the custom avatar or the original URL if not set.
 */
add_filter('get_avatar_url', function($url, $id_or_email, $args) {
    if (is_admin() && $GLOBALS['pagenow'] === 'options-discussion.php') return $url;

    if ($id_or_email instanceof WP_Comment) {
        $id_or_email = $id_or_email->user_id;
    }

    $custom_avatar_id = get_user_meta($id_or_email, 'csfpp_avatar', true);
    if (!$custom_avatar_id || !wp_attachment_is_image($custom_avatar_id)) return $url;

    return wp_get_attachment_image_src($custom_avatar_id, $args['size'])[0];
}, 15, 3);