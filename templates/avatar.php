<?php defined( 'ABSPATH' ) || exit;

/**
 * Template for displaying user avatar.
 * This template can be overridden by copying it to yourtheme/templates/csfpp/avatar.php.
 *
 * @since 1.0.0
 */

// Translators: %s is the user's name.
$avatar_alt_text = sprintf(esc_attr__("%s's avatar", 'codeablepress-simple-frontend-profile-picture-upload'), $atts['user_name']);
$avatar_classes = 'csfpp-image rounded-full h-full w-16 ' . esc_attr($atts['classes']);
$avatar_img = get_avatar($atts['owner_id'], 96, '', $avatar_alt_text, array('class' => $avatar_classes));

if (is_user_logged_in() && ($atts['owner_id'] === $atts['user_id'])) {
    $upload_title_text = esc_attr__("Allowed image: JPG, JPEG, PNG, GIF, WEBP", 'codeablepress-simple-frontend-profile-picture-upload');
    echo sprintf(
        '<span class="csfpp-upload cursor-pointer relative" title="%s">%s</span>',
        esc_attr($upload_title_text),
        wp_kses_post($avatar_img)
    );
} else {
    echo wp_kses_post($avatar_img);
}