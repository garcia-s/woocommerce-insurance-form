<?php


class WC_Insurance
{

    private $insurance_entries =  [];
    private static WC_Insurance | null  $instance;
    private const OFFSET = 1000000;
    private const VARIABLE_PERCENTAGE = 0.1;

    private function __construct()
    {
        new WC_Insurance_Api($this);
        add_filter('woocommerce_get_cart_item_from_session', array($this, 'custom_get_cart_item_from_session'), 10, 3);
        add_shortcode('insurance_form_shortcode', array($this, 'create_shortcode'));
        add_filter('woocommerce_data_stores', array($this, 'set_insurance_data_store'));
        add_filter("woocommerce_product_class", array($this, "add_woocommerce_class_for_product"), 10, 2);
        add_filter("woocommerce_product_type_query", array($this, "change_product_type"), 10, 2);
        add_action('wp_loaded', array($this, 'conditionally_load_cart'), 5);
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'custom_insert_data_into_order'), 10, 4);
        add_filter("woocommerce_order_item_get_formatted_meta_data", array($this, "unset_specific_order_item_meta_data"), 10, 2);
        add_action('woocommerce_before_order_notes', array($this, 'add_order_disclaimer'));
    }

    public static function get(): WC_Insurance
    {
        if (!isset(self::$instance))
            self::$instance = new WC_Insurance();
        return self::$instance;
    }

    public static function get_variable_expense_percentage()
    {
        return self::VARIABLE_PERCENTAGE;
    }

    public function register_entry(IWC_Insurance_Entry $entry)
    {
        array_push($this->insurance_entries, $entry);
    }

    public function get_by_id(int $id): IWC_Insurance_Entry|null
    {
        for ($i = 0; $i < count($this->insurance_entries); $i++) {
            if ($i === $id - self::OFFSET)
                return $this->insurance_entries[$i];
        }
        return null;
    }

    public function get_id_from_name(string &$name)
    {
        for ($i = 0; $i < count($this->insurance_entries); $i++) {
            if ($this->insurance_entries[$i]->get_slug() == $name) {
                return $i + self::OFFSET;
            }
        }
        return -1;
    }

    public function get_by_name(&$name): IWC_Insurance_Entry|null
    {
        for ($i = 0; $i < count($this->insurance_entries); $i++) {
            if ($this->insurance_entries[$i]->get_slug() == $name) {
                return $this->insurance_entries[$i];
            }
        }
        return null;
    }

    public function create_shortcode()
    {

        wp_enqueue_script('insurance_script', INSURANCE_URL . 'assets/js/insurance-form.js', array('jquery'), '1.0', true);
        wp_enqueue_style('insurance_styles', INSURANCE_URL . 'assets/css/insurance-form.css', array(), '1.0', 'all');
        ob_start();
?>
        <div id="insurance_content_wrapper">
            <span id="insurance_loader" class="show"></span>
            <form id="insurance_form">
                <div class="contact_information_section">
                    <p> This is an exclusive service for companies affiliated to the BBB we invite you to visit if you are not yet an affiliate
                        <a href="https://www.bbb.org/get-listed">https://www.bbb.org/get-listed</a>
                    </p>
                    <h5>CONTACT INFORMATION</h5>
                    <label>Contact Name </label>
                    <div id="contact_name" class="error"></div>
                    <input placeholder="ie. John Doe" type="text" name="contact_name" />
                    <label>Phone</label>
                    <div id="contact_phone" class="error"></div>
                    <input type="tel" placeholder="8005550175" name="contact_phone" pattern="[0-9]{10}" />
                    <label>Email</label>
                    <div id="contact_email" class="error"></div>
                    <input placeholder="ie. johndoe@example.com" type="email" name="contact_email" />
                    <label>Promotional Code</label>
                    <div id="promo_code" class="error"></div>
                    <input placeholder="PR0MOC0D3" type="text" name="promo_code" />
                </div>
                <div class="contact_information_section">
                    <h5>BUSINESS INFORMATION</h5>
                    <label>NAICS Class</label>
                    <div id="naics_list" class="error"></div>
                    <select name="naics_list" \>
                        <option> Select an NAICS class</option>
                        <?
                        global $naics_list;
                        foreach ($naics_list as $key => $value ) {
                            echo '<option value=' .$key . '>' . $value . '</option>';
                        } ?>
                    </select>
                </div>
                <div class=" insurance_type_section">
                    <h5>INSURANCE TYPE</h5>
                    <div id="insurance_type" class="error"></div>
                    <?php
                    foreach ($this->insurance_entries as $id => $entry) {
                        echo "<div><input type='radio' name='insurance_type' value='{$entry->get_slug()}' /><label>{$entry->get_name()}</label></div>";
                    }
                    echo "</div>";
                    echo '<div id="insurance_section_wrapper">';

                    foreach ($this->insurance_entries as $id => $entry) {
                        echo "<div id='{$entry->get_slug()}' class='insurance_options'>";
                        echo '<h5>INSURANCE OPTIONS</h5>';
                        $entry->renderForm();
                        echo "</div>";
                    }
                    ?>
                </div>
                <button class="insurance_button send">NEXT</button>

            </form>
            <div id="insurance_preview_wrapper">
                <iframe id="insurance_preview_frame"></iframe>
                <div class="preview_button_wrapper">
                    <button class="insurance_button back" id="go_back">
                        Go back
                    </button>
                    <button class="insurance_button send" id="send_insurance">
                        Add to cart
                    </button>
                </div>
            </div>
            <div id="insurance_completed">
                <img src="<?php echo (INSURANCE_URL . "./assets/icons/checkmark.svg"); ?>" </div>
                <p> Your insurance policy has been added to the cart, you can now </p>
                <a class="insurance_button" href="<?php echo wc_get_cart_url() ?>">Go to payment</a>
                <a class="insurance_button" href="<?php echo get_permalink() ?>">Buy another policy</a>
                <a class="insurance_button" href="/">Go to home page</a>
            </div>
    <?php
        return ob_get_clean();
    }

    function set_insurance_data_store($stores)
    {
        $stores['product-insurance'] = 'WC_Insurance_Data_Store';
        return $stores;
    }

    function add_woocommerce_class_for_product($_, $type)
    {
        if ($type == "insurance") return "WC_Product_Insurance";
    }

    function change_product_type($type, $productId)
    {
        if (null !== $this->get_by_id($productId))
            return "insurance";
        return $type;
    }

    function conditionally_load_cart()
    {

        if (WC()->is_rest_api_request()) {
            if (empty($_SERVER['REQUEST_URI'])) {
                return;
            }

            $rest_prefix = 'insurance/v1';
            $req_uri     = esc_url_raw(wp_unslash($_SERVER['REQUEST_URI']));

            $is_my_endpoint = (false !== strpos($req_uri, $rest_prefix));

            if (!$is_my_endpoint) {
                return;
            }

            require_once WC_ABSPATH . 'includes/wc-cart-functions.php';
            require_once WC_ABSPATH . 'includes/wc-notice-functions.php';

            if (null === WC()->session) {
                $session_class = apply_filters('woocommerce_session_handler', 'WC_Session_Handler');

                // Prefix session class with global namespace if not already namespaced
                if (false === strpos($session_class, '\\')) {
                    $session_class = '\\' . $session_class;
                }

                WC()->session = new $session_class();
                WC()->session->init();
            }


            if (is_null(WC()->customer)) {
                if (is_user_logged_in()) {
                    WC()->customer = new WC_Customer(get_current_user_id());
                } else {
                    WC()->customer = new WC_Customer(get_current_user_id(), true);
                }
                add_action('shutdown', array(WC()->customer, 'save'), 10);
            }

            if (WC()->cart == null) {
                WC()->cart = new WC_Cart();
            }
        }
    }

    function custom_get_cart_item_from_session($session_data, $value, $key)
    {

        if ($session_data["data"]->get_type() !== "insurance") {
            return $session_data;
        }
        $entry = $this->get_by_id($session_data['product_id']);
        if ($entry == null)
            return nulladd_action('woocommerce_new_order', 'custom_insert_data_into_order', 10, 1);;

        $entry->set_data($session_data["insurance-data"]);
        if (!empty($entry->validate($session_data)))
            return null;

        $entry->calculatePremium($session_data);
        $product = $session_data["data"];
        $product->set_price($entry->get_premium());
        return $session_data;
    }


    function custom_insert_data_into_order($item, $cart_item_key, $values, $order)
    {
        $cart_item = WC()->cart->get_cart_item($cart_item_key);
        if ($cart_item["insurance-data"] != null) {
            $item->add_meta_data("insurance-data", json_encode($cart_item["insurance-data"]), false);
        }
        $item->save();
    }



    function unset_specific_order_item_meta_data($formatted_meta, $item)
    {
        foreach ($formatted_meta as $key => $meta) {
            if ($meta->key == "insurance-data")
                unset($formatted_meta[$key]);
        }
        return $formatted_meta;
    }

    function add_order_disclaimer()
    {
        echo '<p> Your order has been received a note that says please check your spam email if you do not receive a copy of your order details in your inbox.</p>';
    }
}
