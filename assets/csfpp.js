jQuery(document).ready(function($) {
    // Cache selectors
    const $body = $('body');
    const $avatarImage = $('.csfpp-upload img');
    const $avatarDisplay = $('.csfpp-image');
    const { nonce, ajax_url } = csfpp_avatar_upload_vars;

    // Create and append the hidden file input once
    const $fileInput = $('<input>', {
        type: 'file',
        name: 'profile_picture',
        class: 'hidden w-full h-full opacity-0 z-2 t-0 l-0 absolute'
    }).appendTo($body);

    // Event listener for avatar image area click
    $avatarImage.on('click.csfpp', function() {
        $fileInput.click();
    });

    // Handle file selection
    $fileInput.on('change.csfpp', debounce(function() {
        const file = this.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            return;
        }

        const img = new Image();
        img.src = URL.createObjectURL(file);

        img.onload = function() {
            SmartCrop.crop(img, { width: 140, height: 140 }, function(result) {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = result.topCrop.width;
                canvas.height = result.topCrop.height;
                ctx.drawImage(img, result.topCrop.x, result.topCrop.y, result.topCrop.width, result.topCrop.height, 0, 0, canvas.width, canvas.height);

                canvas.toBlob(function(blob) {
                    const formData = new FormData();
                    formData.append('file', blob, file.name);
                    formData.append('action', 'csfpp_upload_profile_picture');
                    formData.append('security', nonce);

                    $.ajax({
                        url: ajax_url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success && response.data.avatar_url) {
                                const newUrl = `${response.data.avatar_url}?t=${new Date().getTime()}`;
                                $avatarDisplay.attr({
                                    'src': newUrl,
                                    'srcset': newUrl
                                });
                            } else {
                                const message = response.data.message || 'An unexpected error occurred. Please try again.';
                                const safeMessage = $('<div>').text(message).html();
                                alert(safeMessage);
                            }
                        },
                        error: function() {
                            alert('An error occurred while uploading the profile picture.');
                        }
                    });
                }, 'image/jpeg');
            });
        };
    }, 300));

    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
});