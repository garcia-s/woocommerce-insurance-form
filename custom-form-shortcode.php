<?php

function custom_form_shortcode() {
    ob_start();
    // Your form HTML goes here
    ?>
    <form method="post" action="">
        <!-- Your form fields and content here -->
        <input type="text" name="name" placeholder="Your Name">
        <input type="email" name="email" placeholder="Your Email">
        <textarea name="message" placeholder="Your Message"></textarea>
        <input type="submit" value="Submit">
    </form>
    <?php
    return ob_get_clean();
}

// Register the shortcode
add_shortcode('coi_form', 'custom_form_shortcode');
