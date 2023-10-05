<?php

require_once(WC_INSURANCE_DIR . './loss_of_key_person/loss_of_key_person_module.php');
require_once(WC_INSURANCE_DIR . './workplace_violence/workplace_violence_module.php');


add_shortcode('insurance_form_shortcode', 'insurance_form_function');


function insurance_form_function()
{
    wp_enqueue_script('insurance_script', INSURANCE_URL . 'assets/js/insurance-form.js', array('jquery'), '1.0', true);
    wp_enqueue_style('insurance_styles', INSURANCE_URL . 'assets/css/insurance-form.css', array(), '1.0', 'all');
    return renderForm();
}



function renderForm()
{
    ob_start();
?>
    <div class="insurance_form_wrapper">
        <form id="insurance_form">
            <div class="contact_information_section">
                <h4>Contact Information</h4>
                <div class='insurance_error' id="contact_name_error"></div>
                <label>Contact Name </label><input type="text" name="contact_name" />
                <div class='insurance_error' id="contact_number_error"></div>
                <label>Phone</label><input type="text" name="contact_phone" />
                <div class='insurance_error' id="contact_email_error"></div>
                <label>Email</label><input type="text" name="contact_email" />
                <div class='insurance_error' id="fax_error"></div>
                <label>Fax</label><input type="text" name="fax" />
            </div>
            <div class="insurance_type_section">
                <h5>Select an insurance type</h4>
                    <div><input type="radio" name="insurance_type" value="loss_of_key_person" /><label>Loss of key person</label></div>
                    <div><input type="radio" name="insurance_type" value="workplace_violence" /><label>Workplace Violence</label></div>
                    <div><input type="radio" name="insurance_type" value="cyber" /><label>Cyber</label></div>
            </div>
            <div id="insurance_section_wrapper">
                <?php
                load_loss_of_key_person_form();
                load_workplace_violence_form();
                ?>
            </div>
            <button>Next</button>
        </form>
    </div>
<?php
    return ob_get_clean();
}
