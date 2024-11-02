=== CodeablePress: Simple Frontend Profile Picture Upload ===
Contributors: codeablepress
Tags: profile picture, avatar, WooCommerce, user profile, frontend
Requires at least: 5.8
Tested up to: 6.6
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPL-3.0-or-later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A simple, lightweight, and secure way for users to upload profile pictures directly from the WooCommerce My Account page or via shortcode.

== Description ==

**CodeablePress: Simple Frontend Profile Picture Upload** provides a seamless way for users to upload profile pictures directly from their WooCommerce My Account page or using a shortcode. The plugin is designed with simplicity, security, and performance in mind, ensuring an efficient user experience.

**Features:**
 * Seamlessly set a default image using a simple filter
 * Intelligent cropping to identify the best focal point, ensuring a perfectly squared photo on upload
 * Lightweight shortcode to display other users avatars and current users avatar uploader anywhere on your site
 * Automatically integrates an avatar display and upload option in the WooCommerce 'My Account' page
 * Effortlessly manages old profile pictures by automatically deleting them upon new uploads
 * Easily upload or delete custom avatars directly from the Admin user's profile area
 * Ultra-light plugin with a file size of less than 0.05MB
 * Very high emphasis on security 
 * Fully translation-ready with a .pot file included for easy localization
 * Supports multiple image formats (JPG, JPEG, PNG, GIF, WEBP)
 * Regular updates and dedicated support for seamless integration

This plugin is perfect for websites that need a straightforward solution for users to update their profile pictures from the frontend.

== Installation ==

1. Upload the plugin files to the /wp-content/plugins/custom-profile-picture-for-woocommerce directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Plugin will automatically display users avatar with upload option above the WooCommerce My Account menu.
4. Use the [csfpp_avatar] shortcode to display avatars on additional pages or posts.

Usage:

    Shortcode: [csfpp_avatar]
        Attributes:
            check_page_author: Check the page author and display their avatar instead of current users (default: false).
            classes: Additional CSS classes to apply (default: '').
	    type: Decides what content to display. Type 0. Avatar only | Type 1. Avatar and name | Type 2. Avatar, name and profile link (default: 0)

Full use example:
[csfpp_avatar check_page_author="false" classes="" type="0"]

== Frequently Asked Questions ==

= Can I set a default avatar?= 
Yes! You can set the path with a simple filter. 

1. Upload your default image to wp-content/uploads/.
2. change default-profile-picture.png to the name of your image in the code below.
3. Add the code to your functions.php file.

// Set default avatar image
add_filter('get_avatar_url', function($url, $id_or_email, $args) {
    // Check if the URL is empty or not an image URL
    if (empty($url) || !wp_attachment_is_image($url)) {
        // Update this to the relative path of the image in the uploads directory
        $default_image_path = 'default-profile-picture.png';

        // Upload file to wp-content/uploads/
        return trailingslashit(wp_upload_dir()['baseurl']) . $default_image_path;
    }

    return $url;
}, 10, 3);

= How do I remove the avatar above the woocommerce menu in My Account? = 
Add the following code to your functions.php file to remove the profile picture above the WooCommerce My Account menu.
remove_action('woocommerce_before_account_navigation', 'csfpp_display_avatar_with_name');

= How do I stop the avatar from being round? =
The avatar design is controlled by a template that can be placed in your theme folder. Copy the avatar.php file from the templates folder of the plugin and place it in yourtheme/templates/csfpp/avatar.php

Open the template file in your themes folder and remove the text "rounded-full".

= Can I delete or change other user's avatars? = 
Yes. Simply navigate to their profile page in the admin area.

Delete: Click the delete button that shows if the user has a custom profile (not gravatar).
Upload: Click on the users profile photo, upload a photo with the media uploader, save the profile.

= Does gravatar still work and can I disable it? =
Yes, gravatar still works. If you'd like to disable it, you can use the following functions in your function.php file. Be sure to set a new default avatar after doing so in discussion.php

// Disable Gravatars
add_filter('get_avatar', function($avatar, $id_or_email, $size, $default, $alt) {
    return '';
}, 1, 5);

// Remove Gravatar from Discussion Settings
add_filter('avatar_defaults', function($avatar_defaults) {
    unset($avatar_defaults['mystery']);
    unset($avatar_defaults['blank']);
    unset($avatar_defaults['gravatar_default']);
    return $avatar_defaults;
});

// Hide the Avatar Settings Section
add_action('admin_init', function() {
    remove_action('admin_init', 'avatar_settings_init');
});

= How can I change the default size of uploaded avatars? =
The default size for avatars in this plugin is based on your WordPress settings for thumbnail size. You can easily change this by following these steps:

1. Go to WordPress Admin Settings:
Navigate to Settings > Media in your WordPress dashboard.
Under the "Thumbnail size" section, you’ll see options to set the width and height for thumbnails. Adjust these values to your desired dimensions.

2. Override Default Size (Advanced Users):
If you prefer to set a specific size for avatars that differs from your general thumbnail settings, you can modify the code where the avatar upload size is defined. This is typically done in the plugin's settings or by adding a custom function in your theme's functions.php file.

add_filter('csfpp_avatar_size', function() {	
	return [150, 150]; // Change 150x150 to your preferred dimensions
});

== License ==

This plugin is licensed under the GPL-3.0-or-later license. For more information, please visit GPL-3.0 License.

== Changelog ==

= 1.0.0 - 2024-10-28
* Initial full release!