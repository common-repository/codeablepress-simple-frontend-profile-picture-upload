<?php defined( 'ABSPATH' ) || exit;

/**
 * Template for displaying user avatar and information.
 * This template can be overridden by copying it to yourtheme/templates/csfpp/avatar-with-content.php.
 *
 * @since 1.0.0
 */ 
?>

<div id="csfpp-container" class="pb-4 flex items-center">
    <?php csfpp_load_avatar_template($atts); ?>
    <div class="csfpp-username ml-4">
        <?php echo esc_html($atts['user_name']); ?>
        <em class="csfpp-user-id opacity-50">#<?php echo esc_html($atts['owner_id']); ?></em>
        <?php if ($atts['type'] === 2) : ?>
            <div><a href="<?php echo esc_url(get_author_posts_url($atts['owner_id'])); ?>">view profile</a></div>
        <?php endif; ?>
    </div>
</div>