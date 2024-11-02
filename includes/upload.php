<?php defined( 'ABSPATH' ) || exit;

/**
 * Handles the AJAX request to securely upload and resize a profile picture.
 *
 * @since 1.0.0
 */
add_action('wp_ajax_csfpp_upload_profile_picture', function() {
    // Nonce check for security.
    if (!check_ajax_referer('csfpp_profile_picture_upload_nonce', 'security', false)) {
        wp_send_json_error(['message' => esc_html__('Security check failed.', 'codeablepress-simple-frontend-profile-picture-upload')]);
        return;
    }

    // Check if file is uploaded.
    if (empty($_FILES['file']['name'])) {
        wp_send_json_error(['message' => esc_html__('No file provided.', 'codeablepress-simple-frontend-profile-picture-upload')]);
        return;
    }

    // Validate user id.
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(['message' => esc_html__('You must be logged in to upload an avatar.', 'codeablepress-simple-frontend-profile-picture-upload')]);
        return;
    }

    // Sanitize file name
    $file = [
        'name'     => isset($_FILES['file']['name']) ? sanitize_file_name($_FILES['file']['name']) : '',
        'type'     => isset($_FILES['file']['type']) ? sanitize_mime_type($_FILES['file']['type']) : '',
        'tmp_name' => isset($_FILES['file']['tmp_name']) ? sanitize_text_field($_FILES['file']['tmp_name']) : '',
        'error'    => isset($_FILES['file']['error']) ? intval($_FILES['file']['error']) : 0,
        'size'     => isset($_FILES['file']['size']) ? intval($_FILES['file']['size']) : 0,
    ];

    // Validate file type and extension
    $file_path = $file['tmp_name'];
    $file_type = wp_check_filetype_and_ext($file_path, $file['name']);
    $allowed_types = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_type['type'], $allowed_types) || !in_array($extension, $allowed_extensions)) {
        wp_send_json_error(['message' => esc_html__('Uploaded file type must be JPG, JPEG, PNG, GIF, WEBP.', 'codeablepress-simple-frontend-profile-picture-upload')]);
        return;
    }

    // Ensure the file is a valid image.
    if (!getimagesize($file_path)) {
        wp_send_json_error(['message' => esc_html__('Uploaded file is not a valid image.', 'codeablepress-simple-frontend-profile-picture-upload')]);
        return;
    }

    // Use WP_Image_Editor to handle image processing (stripping metadata, resizing, etc.), if available.
    $image_editor = wp_get_image_editor($file_path);
    if (!is_wp_error($image_editor)) {

        // Strip metadata (EXIF data) if the editor supports it.
        if (method_exists($image_editor, 'strip_image')) {
            $image_editor->strip_image(); // Available in Imagick editor
        }

        // Convert to a safe format (jpg).
        $new_file_path = preg_replace('/\.[^.]+$/', '.jpg', $file_path);
        if (method_exists($image_editor, 'save')) {
            $saved = $image_editor->save($new_file_path, 'image/jpeg'); 
            if (is_wp_error($saved)) {
                wp_send_json_error(['message' => esc_html__('Failed to convert image format.', 'codeablepress-simple-frontend-profile-picture-upload')]);
                return;
            }
        }
    }

    // Attempt to delete old avatar.
    csfpp_delete_users_old_avatar($user_id);

   // Upload the processed file.
    $upload_overrides = ['test_form' => false];
    $uploaded = wp_handle_upload($file, $upload_overrides);
    if (isset($uploaded['error'])) {
        wp_send_json_error(['message' => esc_html($uploaded['error'])]);
        return;
    }

    // Insert the uploaded image into the media library.
    $attachment = array(
        'guid'           => esc_url_raw($uploaded['url']),
        'post_mime_type' => sanitize_mime_type($uploaded['type']),
        'post_title'     => sanitize_file_name(preg_replace('/\.[^.]+$/', '', basename($uploaded['file']))),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );
    $attach_id = wp_insert_attachment($attachment, $uploaded['file']);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded['file']);
    wp_update_attachment_metadata($attach_id, $attach_data);

    // Resize the image.
    $image_editor = wp_get_image_editor($uploaded['file']);
    if (!is_wp_error($image_editor)) {
        $thumbnail_size = csfpp_get_upload_size();
        if ($thumbnail_size[0] > 0 && $thumbnail_size[1] > 0) {
            if (method_exists($image_editor, 'resize')) {
                $image_editor->resize($thumbnail_size[0], $thumbnail_size[1], true);
            }
            
            if (method_exists($image_editor, 'save')) {
                $image_editor->save($uploaded['file']);
            }
        }
    }

    // Update user meta with new avatar.
    update_user_meta($user_id, 'csfpp_avatar', $attach_id);

    // Respond with success.
    $attach_url = wp_get_attachment_url($attach_id);
    wp_send_json_success([
        'message' => esc_html__('Avatar uploaded successfully.', 'codeablepress-simple-frontend-profile-picture-upload'), 
        'avatar_url' => esc_url($attach_url)
    ]);
});

/**
 * Handle avatar upload attempts by non-logged-in users.
 * 
 * @since 1.0.0
 */
add_action('wp_ajax_nopriv_csfpp_upload_profile_picture', function() {
    wp_send_json_error(['message' => esc_html__('You must be logged in to upload an avatar.', 'codeablepress-simple-frontend-profile-picture-upload')]);
});