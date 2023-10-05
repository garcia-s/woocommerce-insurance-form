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

        register_rest_route('insurance/v1', '/get_premium', array(
            'methods' => 'POST',
            'callback' => array($this, 'get_premium_callback'),
            'permission_callback' => "__return_true"

        ));

        register_rest_route('insurance/v1', '/get-pdf-preview', array(
            'methods' => 'POST',
            'callback' => array($this, 'generate_pdf'),
            'permission_callback' => "__return_true"
        ));
    }

    function process_insurance_request($request)
    {
    }


    function validateFormInformation(&$sanitizedData)
    {

        if (!isset($sanitizedData["contact_name"]) || $sanitizedData["contact_name"] == "")
            $errors["contact_name"] = "The contact name cannot be empty";

        if (!isset($sanitizedData["contact_phone"]) || $sanitizedData["contact_phone"] == "")
            $errors["contact_phone"] = "The phone number cannot be empty";

        if (!isset($sanitizedData["contact_email"]) || $sanitizedData["contact_email"] == "")
            $errors["contact_email"] = "The email cannot be empty";

        if (!filter_var($sanitizedData["contact_email"], FILTER_VALIDATE_EMAIL))
            $errors["contact_email"] = "The email cannot be empty";

        if (!isset($sanitizedData["fax"]) || $sanitizedData["fax"] == "")
            $errors["fax"] = "The email cannot be empty";


        if (!isset($sanitizedData["insurance_type"]) ||  empty($sanitizedData["insurance_type"])) {
            $errors["insurance_type"] == "You must select an insurance type";
        }

        $insurance = $this->instance->get_by_name($sanitizedData['insurance_type']);

        if (null === $insurance)
            $errors["insurance_type"] == "The insurance type provided is not valid";

        $errors = [$errors, ...$insurance->validate($sanitizedData)];
        ///^\+?[0-9]{7,}$/  FOR FAX
        return $errors;
    }
    function generate_pdf()
    {




        require(WC_INSURANCE_DIR . '/workplace_violence/wv_data.php');

        $sanitizedData = [];
        foreach ($request->get_json_params() as $key => $value) {
            $sanitizedData[$key] = sanitize_text_field($value);
        }

        $errors = $this->validateFormInformation($sanitizedData);

        if (!empty($errors))
            return new WP_REST_Response($errors, 400);


        require(WC_INSURANCE_DIR . 'vendor/autoload.php');
        // Create a PDF instance

        $pdf = new TCPDF();
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();
        $pdf->SetFont('times', '', 12);

        $name = ['contact_name'];
        $phone = ['contact_phone'];
        $email = ['contact_email'];
        $fax = ['fax'];

        // Add content to the PDF
        $content = "
            <h1>Insurance Information</h1>
            <p>Name: $name</p>
            <p>Phone: $phone</p>
            <p>Email: $email</p>
            <p>Fax: $fax</p>
        ";

        $pdf->writeHTML($content, true, false, true, false, '');
        $pdf->Output('insurance_information.pdf', 'D');

        return new WP_REST_Response(["status" => "OK"], 200);
    }

    function get_premium_callback()
    {
    }
}
