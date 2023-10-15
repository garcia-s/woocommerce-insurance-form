<?php

class WC_Insurance_Api
{
    private WC_Insurance $instance;
    function __construct(WC_Insurance &$instance)
    {
        $this->instance = $instance;
        add_action('rest_api_init', array($this, 'register_insurance_endpoint'));
    }

    function register_insurance_endpoint()
    {
        register_rest_route('insurance/v1', '/submit', array(
            'methods' => 'POST',
            'callback' => array($this, 'process_insurance_request'),
            'permission_callback' => "__return_true"
        ));

        register_rest_route('insurance/v1', '/get-pdf-preview', array(
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

        if (!is_null($insurance))
            $errors = array_merge($errors, $insurance->validate($sanitizedData));

        if (!empty($errors))
            return new WP_REST_Response($errors, 400);

        $insurance->calculatePremium($sanitizedData);
        WC()->cart->add_to_cart($this->instance->get_id_from_name($sanitizedData['insurance_type']), 1, 0, array(), $sanitizedData);
        // dnd($this->instance->get_id_from_name($sanitizedData['insurance_type']));

        return new WP_REST_Response(["success" => "You succesfully added the insurance to the cart", "cart" => WC()->cart], 200);
    }


    function validateFormInformation(&$sanitizedData)
    {
        $errors = [];

        if (!isset($sanitizedData["contact_name"]) || $sanitizedData["contact_name"] == "")
            $errors["contact_name"] = "the contact name cannot be empty";

        if (!isset($sanitizedData["contact_phone"]) || $sanitizedData["contact_phone"] == "")
            $errors["contact_phone"] = "the phone number cannot be empty";

        if (!isset($sanitizedData["contact_email"]) || $sanitizedData["contact_email"] == "")
            $errors["contact_email"] = "the email cannot be empty";

        if (!filter_var($sanitizedData["contact_email"], FILTER_VALIDATE_EMAIL))
            $errors["contact_email"] = "please provide a valid email";

        if (!isset($sanitizedData["fax"]) || $sanitizedData["fax"] == "")
            $errors["fax"] = "the fax number cannot be empty";

        if (!isset($sanitizedData["insurance_type"]) ||  empty($sanitizedData["insurance_type"]))
            $errors["insurance_type"] == "You must select an insurance type";
        ///^\+?[0-9]{7,}$/  FOR FAX
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

        if (is_null($insurance))
            $errors["insurance_type"] == "The insurance type provided is not valid";

        if (!is_null($insurance))
            $errors = array_merge($errors, $insurance->validate($sanitizedData));

        if (!empty($errors))
            return new WP_REST_Response($errors, 400);

        $insurance->calculatePremium($sanitizedData);

        $pdf = $this->generate_pdf($sanitizedData);
        $pdf->Output('insurance_information.pdf', 'D');
        return new WP_REST_Response(["status" => "OK"], 200);
    }
    function generate_pdf(&$request)
    {

        require(WC_INSURANCE_DIR . 'vendor/autoload.php');
        // Create a PDF instance-


        $pdf = new TCPDF();
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);

        $name = $sanitizedData['contact_name'];
        $phone = $sanitizedData['contact_phone'];
        $email = $sanitizedData['contact_email'];
        $fax = $sanitizedData['fax'];

        // Add content to the PDF

        $content = "
<html lang='en'>
<head>
    <meta charset=UTF-8>
    <meta name='viewport content='width=device-width, initial-scale=1.0'>
    <title>Insurance Information</title>
</head>
<body>
    <div class='container'>
        <!-- Logo -->
        <img src='path/to/your/logo.png' alt='Company Logo' class='logo'>

        <!-- Contact Information -->
        <div class='contact-info'>
            <h2>Contact Information</h2>
            <p><strong>Contact Name:</strong> $name </p>
            <p><strong>Contact Phone:</strong> $phone</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Fax:</strong>$fax</p>
        </div>

        <!-- Insurance Information -->
        <div class='premium'>
            <h2>Insurance Information</h2>
            <p><strong>Type of Insurance:</strong></p>
            <p><strong>Premium:</strong> $[Premium]</p>
        </div>
    </div>
</body>
</html>";

        $pdf->writeHTML($content, true, false, true, false, '');
        return $pdf;
    }

    function sendPDFEmail($email, $pdfPath)
    {
        $separator = md5(time());

        $headers = "MIME-Version: 1.0";
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"";
        $headers .= "Content-Transfer-Encoding: 7bit";

        $subject = 'You have purchased an insurance policy';
        $message = 'Thankyou for trusting us with your information and safety, we will help you with everything you need,
        we have attached the insurance policy contract to this email please feel free ask any questions, regards.';

        // Send the email$attachment = array($filename);
        $attachment = array($pdfPath);
        wp_mail($email, $subject, $message, $headers, $attachment);
        unlink($pdfPath); // Deletion of the created file.t
    }
}
