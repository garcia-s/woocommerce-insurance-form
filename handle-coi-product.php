<?php
function add_product_custom_meta_box()
{
    add_meta_box(
        'product_requires_coi',
        __('Requires COI', 'textdomain'),
        'product_requires_coi_callback',
        'product',
        'normal',
        'high'
    );
}


add_action('add_meta_boxes', 'add_product_custom_meta_box');

function product_requires_coi_callback($post)
{
    $requires_coi = get_post_meta($post->ID, '_requires_coi', true);
    
    wp_nonce_field('product_requires_coi', 'product_requires_coi_nonce');
?>
    <label for="requires_coi">
        <input type="checkbox" name="requires_coi" id="requires_coi" value="1" <?php checked($requires_coi, '1'); ?>>
        <?php _e('Requires COI', 'textdomain'); ?>
    </label>
<?php
}


function save_product_requires_coi($post_id)
{

    if (
        !isset($_POST['product_requires_coi_nonce']) &&
        !wp_verify_nonce($_POST['product_requires_coi_nonce'], 'product_requires_coi') &&
        defined('DOING_AUTOSAVE') && DOING_AUTOSAVE &&
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $requires_coi = isset($_POST['requires_coi']) ? '1' : '0';
    update_post_meta($post_id, '_requires_coi', $requires_coi);
}

add_action('save_post', 'save_product_requires_coi');
