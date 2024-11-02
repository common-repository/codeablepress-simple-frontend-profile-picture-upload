<?php defined( 'ABSPATH' ) || exit;

/**
 * Loads the avatar template with the provided arguments.
 *
 * @since 1.0.0
 *
 * @param array $atts {
 *     Array of arguments to customize the avatar display.
 *
 *     @type int    $type              Type of display: 0 for avatar only. Default 0.
 *     @type bool   $check_page_author Whether to determine the user ID based on the type of page being viewed. Default false.
 *     @type string $classes           Additional CSS classes to apply to the outer container. Default empty string.
 *     @type int    $user_id           The current user's ID.
 *     @type int    $owner_id          The owner of the avatar, determined by the page context.
 * }
 */
function csfpp_load_avatar_template($atts = array()) {
    csfpp_enqueue_styles_and_scripts();

    $template_path = locate_template('templates/csfpp/avatar.php');
    if (!$template_path) {
        $template_path = CSFPP_PLUGIN_DIR . 'templates/avatar.php';
    }

    include $template_path;
}

/**
 * Loads the avatar template with additional content like user name, ID, and profile link.
 *
 * @since 1.0.0
 *
 * @param array $atts {
 *     Array of arguments to customize the avatar display with additional content.
 *
 *     @type int    $type              Type of display: 1 for avatar with name and user ID, 2 for avatar with name, user ID, and profile link. Default 1.
 *     @type bool   $check_page_author Whether to determine the user ID based on the type of page being viewed. Default false.
 *     @type string $classes           Additional CSS classes to apply to the outer container. Default empty string.
 *     @type int    $user_id           The current user's ID.
 *     @type int    $owner_id          The owner of the avatar, determined by the page context.
 * }
 */
function csfpp_load_avatar_with_content_template($atts = array()) {
    $template_path = locate_template('templates/csfpp/avatar-with-content.php');
    if (!$template_path) {
        $template_path = CSFPP_PLUGIN_DIR . 'templates/avatar-with-content.php';
    }

    include $template_path;
}