<?php

class WC_Insurance_Api
{
    private WC_Insurance $instance;
    function __construct(WC_Insurance &$instance)
    {
        $this->instance = $instance;
        add_action('rest_api_init', array($this, 'register_insurance_endpoint'));
        add_action('woocommerce_order_status_changed', array($this, 'custom_send_email_on_status_change'), 10, 3);
    }

    function register_insurance_endpoint()
    {
        register_rest_route('insurance/v1', '/submit', array(
            'methods' => 'POST',
            'callback' => array($this, 'process_insurance_request'),
            'permission_callback' => "__return_true"
        ));

        register_rest_route('insurance/v1', '/get-premium', array(
            'methods' => 'POST',
            'callback' => array($this, 'get_preview_callback'),
            'permission_callback' => "__return_true"
        ));
    }

    function process_insurance_request($request)
    {
        $sanitizedData = [];
        foreach ($request->get_json_params() as $key => $value) {
            $sanitizedData[$key] = sanitize_text_field($value);
        }

        $errors = $this->validateFormInformation($sanitizedData);
        $insurance = $this->instance->get_by_name($sanitizedData['insurance_type']);

        if (is_null($insurance))
            $errors["insurance_type"] == "The insurance type provided is not valid";

        if (!is_null($insurance)) {
            $insurance->init($sanitizedData);
            $errors = array_merge($errors, $insurance->validate());
        }
        if (!empty($errors))
            return new WP_REST_Response($errors, 400);

        $insurance->calculatePremium($sanitizedData);

        // $pdfPath = '/tmp/certificate_of_liability_insurace_' . (new DateTime("now"))->format("Y_m_d_H_i_s_u") . '.pdf';
        // $pdf = generate_pdf($sanitizedData, $insurance);
        // $pdf->Output($pdfPath, "F");
        // $this->sendAdminEmailWithPDF($pdfPath);
        // unlink($pdfPath);
        $insuranceId = $this->instance->get_id_from_name($insurance->get_slug());
        WC()->cart->add_to_cart($insuranceId, 1, null, array(), ["insurance-data" => $sanitizedData]);
        return new WP_REST_Response(["success" => "You succesfully added the insurance to the cart"], 200);
    }


    function validateFormInformation(&$sanitizedData)
    {
        $errors = [];

        if (!isset($sanitizedData["contact_name"]) || $sanitizedData["contact_name"] == "")
            $errors["contact_name"] = "The contact name cannot be empty";

        if (!isset($sanitizedData["contact_phone"]) || $sanitizedData["contact_phone"] == "")
            $errors["contact_phone"] = "The phone number cannot be empty";

        if (!isset($sanitizedData["contact_email"]) || $sanitizedData["contact_email"] == "")
            $errors["contact_email"] = "The email cannot be empty";

        if (!filter_var($sanitizedData["contact_email"], FILTER_VALIDATE_EMAIL))
            $errors["contact_email"] = "Please provide a valid email";

        if (!isset($sanitizedData["insurance_type"]) ||  empty($sanitizedData["insurance_type"]))
            $errors["insurance_type"] = "You must select an insurance type";
        ///^\+?[0-9]{7,}$/  FOR FAX
        global $naics_list;
        if (!isset($sanitizedData["naics_list"]) || $naics_list[$sanitizedData["naics_list"]] == null)
            $errors["naics_list"] = "Provide a valid NAICS Class";

        return $errors;
    }

    function get_preview_callback($request)
    {

        $sanitizedData = [];
        foreach ($request->get_json_params() as $key => $value) {
            $sanitizedData[$key] = sanitize_text_field($value);
        }

        $errors = $this->validateFormInformation($sanitizedData);
        $insurance = $this->instance->get_by_name($sanitizedData['insurance_type']);

        if ($insurance === null)
            $errors["insurance_type"] = "The insurance type provided is not valid";

        if ($insurance !== null) {
            $insurance->init($sanitizedData);
            $errors = array_merge($errors, $insurance->validate());
        }

        if (!empty($errors))
            return new WP_REST_Response($errors, 400);

        $insurance->calculatePremium($sanitizedData);
        return new WP_REST_Response(["premium" => $insurance->get_premium()], 200);
    }

    function sendClientEmailWithPDF($email, $pdfPath)
    {
        $separator = md5(time());

        $headers = "MIME-Version: 1.0";
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"";
        $headers .= "Content-Transfer-Encoding: 7bit";

        $subject = 'You have requested an insurance policy';
        $message = 'Thank you for trusting us with your information and safety, we will help you with everything you need,
        we have attached the insurance policy contract to this email please feel free ask any questions, regards.';

        $attachment = array($pdfPath);
        wp_mail($email, $subject, $message, $headers, $attachment);
    }

    function custom_send_email_on_status_change($order_id, $old_status, $new_status)
    {


        if ($old_status === 'approved' || $new_status !== 'approved') {
            return;
        }
        foreach (wc_get_order($order_id)->get_items() as $item_id => $item) {
            $metadata = wc_get_order_item_meta($item_id, "insurace-data", true);
            if ($metadata == '') continue;

            $metadata = json_decode("metadata");
            $insurance = $this->instance->get_by_name($metadata["slug"]);
            $insurance->set_data($metadata);
            $insurance->calculatePremium();

            $pdfPath = '/tmp/certificate_of_liability_insurace_' . (new DateTime("now"))->format("Y_m_d_H_i_s_u") . '.pdf';
            $pdf = generate_pdf($metadata, $insurance);
            $pdf->Output($pdfPath, "F");
            $this->sendClientEmailWithPDF($pdfPath, $metadata["contact_email"]);
            $this->sendAdminEmailWithPDF($pdfPath);
            unlink($pdfPath);
        }
    }

    function sendAdminEmailWithPDF($pdfPath)
    {
        $email = get_option("admin_email");
        $separator = md5(time());

        $headers = "MIME-Version: 1.0";
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"";
        $headers .= "Content-Transfer-Encoding: 7bit";

        $subject = 'Someone has  purchased an insurance policy';
        $message = 'Someone requested an insurance certificate, please read the attached document for more information';

        $attachment = array($pdfPath);
        wp_mail($email, $subject, $message, $headers, $attachment);
    }
}
