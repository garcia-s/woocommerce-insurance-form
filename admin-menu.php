<?php





function editable_pdf_product_metabox()
{
    add_meta_box(
        'editable_pdf_metabox',
        'Editable PDF',
        'editable_pdf_metabox_content',
        'product',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'editable_pdf_product_metabox');

function editable_pdf_metabox_content($post)
{
    $selected_pdf = get_post_meta($post->ID, '_editable_pdf', true);

    $args = array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => -6,
        'post_mime_type' => 'application/pdf',
    );

    $query = new WP_Query($args);
?>
    <label for="editable_pdf">Select PDF:</label>
    <select name="editable_pdf" id="editable_pdf">
        <option value="">Select PDF</option>
        <?php

        while ($query->have_posts()) {
            $query->the_post();
            echo '<option value="' . get_the_id() . '" ' . selected($selected_pdf, get_the_id(),  false) . '>' . get_the_title() . '</option>';
        }
        ?>
    </select>
<?php
}


function editable_pdf_save_product_meta($post_id)
{
    if (isset($_POST['editable_pdf'])) {
        update_post_meta($post_id, '_editable_pdf', sanitize_text_field($_POST['editable_pdf']));
    }
}

add_action('save_post_product', 'editable_pdf_save_product_meta');
