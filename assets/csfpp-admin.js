jQuery(document).ready(function($) {
    const deleteProfilePictureBtn = $('#delete-profile-picture');
    const profilePictureSelector = '.user-profile-picture img.avatar';
    const { nonce, user_id, ajax_url } = csfpp_admin_vars;

    // Event listener for deleting profile picture
    deleteProfilePictureBtn.on('click.csfpp', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete the profile picture?')) {
            $.post(ajax_url, {
                action: 'csfpp_delete_profile_picture',
                nonce,
                user_id
            })
            .done(function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    const message = response.data.message || 'An unexpected error occurred. Please try again.';
                    const safeMessage = $('<div>').text(message).html();
                    alert(safeMessage);
                }
            });
        }
    });

    // Handles uploading of profile picture in admin
    let mediaUploader;
    $(profilePictureSelector).on('click.csfpp', function(e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Choose Profile Picture',
            button: { text: 'Choose Picture' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $(profilePictureSelector).attr('src', attachment.url);
            $('#profile_picture').val(attachment.id); // Hidden input field to store attachment ID
        });

        mediaUploader.open();
    });
});