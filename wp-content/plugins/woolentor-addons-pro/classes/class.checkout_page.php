<?php 

/**
* Checkout page
*/

class WooLentor_Checkout_Page{

    /**
     * [$instance]
     * @var null
     */
    private static $instance = null;

    /**
     * [instance]
     * @return [WooLentor_Checkout_Page]
     */
    public static function instance(){
        if( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] class constructor
     */
    function __construct(){
        $template_settings = get_option('woolentor_woo_template_tabs');
        $checkout_page_id  = !empty($template_settings['productcheckoutpage']) ? $template_settings['productcheckoutpage'] : '';
        $widget_options    = woolentor_pro_get_settings_by_widget_name( $checkout_page_id, 'wl-checkout-order-review' );

        /* 
         * For review order addon
         * Override the template to remove shipping method select option from the order overview
         */
        if( !empty($widget_options['settings']['style']) ){
            add_filter('wc_get_template', [ $this, 'wc_get_template_filter' ], 9999, 5);
        }

        /* For Shipping method addon
        ======================================================= */
        $multi_checkout_status = get_option('woolentor_others_tabs');
        $multi_checkout_status = !empty($multi_checkout_status['multi_step_checkout']) && $multi_checkout_status['multi_step_checkout'] == 'on' ? true : false;

        $widget_options_shipping_method = woolentor_pro_get_settings_by_widget_name( $checkout_page_id, 'wl-checkout-shipping-method' );
        $widget_options_msc_style_2     = woolentor_pro_get_settings_by_widget_name( $checkout_page_id, 'wl-checkout-multi-step-form-style-2' );

        if( !$multi_checkout_status && isset($widget_options_shipping_method['settings']) ){
            add_filter( 'woocommerce_update_order_review_fragments', [$this, 'shipping_method_modify'] );
            add_filter( 'woocommerce_cart_shipping_method_full_label', [$this, 'after_shipping_method'], 10, 2);
        } elseif( $multi_checkout_status && isset($widget_options_msc_style_2['settings']) ){
            add_filter( 'woocommerce_update_order_review_fragments', [$this, 'shipping_method_modify'] );
        }

        /*
         * After change the shipping address when there is not shipping available 
         * for the address. A notice is shown.
         * Add span tag in the notice for styling
         */

        if( isset($widget_options_shipping_method['settings']) || isset($widget_options_msc_style_2['settings']) ){
            add_filter('woocommerce_shipping_may_be_available_html', [$this, 'wrap_with_span']);
            add_filter('woocommerce_no_shipping_available_html', [$this, 'wrap_with_span']);
            add_filter('woocommerce_cart_no_shipping_available_html', [$this, 'wrap_with_span']);
        }

        /*
         * Load woocommerce in elementor editor mode
         */
        add_action('elementor/widget/before_render_content', [$this, 'load_wc_in_elementor'] );
    }

    /**
     * Check if the current page is checkout
     */
    public function is_checkout(){
        $template_settings = get_option('woolentor_woo_template_tabs');
        $checkout_page_id  = !empty($template_settings['productcheckoutpage']) ? $template_settings['productcheckoutpage'] : '';

        $get_page_id = !empty($_GET['post']) ? absint($_GET['post']) : '';

        if( is_checkout() && !is_wc_endpoint_url() || ($get_page_id == $checkout_page_id) ){
            return true;
        }

        return false;
    }

    /**
     * Orverride review-order.php template
     */
    public function wc_get_template_filter( $template, $template_name, $args, $template_path, $default_path ){
        if( !$this->is_checkout() ){
            return $template;
        }
    
        // If checkout registration is disabled and not logged in, the user cannot checkout.
        if ( ! WC()->Checkout()->is_registration_enabled() && WC()->Checkout()->is_registration_required() && ! is_user_logged_in() ) {
            return $template;
        }
    
        if($template_name == 'checkout/review-order.php'){
            $template = wc_locate_template('checkout/review-order.php', 'wl-woo-templates', WOOLENTOR_ADDONS_PL_PATH_PRO. '/wl-woo-templates/');
        }
    
        return $template;
    }

    /**
     * Update the fragment of new shipping method addon
     */
    public function shipping_method_modify( $fragments ) {
        if( !$this->is_checkout() ){
            return $fragments;
        }
    
        $multi_checkout_status = get_option('woolentor_others_tabs');
        $multi_checkout_status = !empty($multi_checkout_status['multi_step_checkout']) && $multi_checkout_status['multi_step_checkout'] == 'on' ? true : false;

        $checkout_page_id           = $this->get_assigned_template('productcheckoutpage');
        $widget_options             = woolentor_pro_get_settings_by_widget_name( $checkout_page_id, 'wl-checkout-shipping-method' );
        $widget_options_msc_style_2 = woolentor_pro_get_settings_by_widget_name( $checkout_page_id, 'wl-checkout-multi-step-form-style-2' );
    
        if( !$multi_checkout_status && !isset($widget_options['settings']) ){
            return;
        }

        if( $multi_checkout_status && !isset($widget_options_msc_style_2['settings']) ){
            return;
        }
    
        ob_start();
        
        wc_cart_totals_shipping_html();
    
        $fragments['.woolentor-checkout__shipping-method'] = '<table class="woolentor-checkout__shipping-method"><tbody>'. ob_get_clean() .'</tbody></table>';
        
        return $fragments;
    }

    /**
     * Custom description for shipping methods
     */
    public function after_shipping_method( $label, $method ){
        if( !$this->is_checkout() ){
            return $label;
        }
    
        $checkout_page_id = $this->get_assigned_template('productcheckoutpage');
        $widget_options = woolentor_pro_get_settings_by_widget_name( $checkout_page_id, 'wl-checkout-shipping-method' );
    
        if( !isset($widget_options['settings']) ){
            return;
        }
    
        switch ( $method->method_id ) {
            case 'flat_rate':
                $label .= !empty($widget_options['settings']['flat_rate_desc']) ? '<span class="woolentor-desc">'. $widget_options['settings']['flat_rate_desc'] : '';
                break;
            
            case 'local_pickup':
                $label .= !empty($widget_options['settings']['local_pickup_desc']) ? '<span class="woolentor-desc">'. $widget_options['settings']['local_pickup_desc'] .'</span>' : '';
                break;
    
            case 'free_shipping':
                $label .= !empty($widget_options['settings']['free_shipping_desc']) ? '<span class="woolentor-desc">'. $widget_options['settings']['free_shipping_desc'] .'</span>' : '';
                break;
        }
    
        return $label;
    }

    public function wrap_with_span( $content ){
        if( is_checkout() ){
            $content = '<span class="woolentor-shipping-alert"><i class="eicon-alert"></i>' . $content . '</span>';
        }
    
        return $content;
    }

    public function load_wc_in_elementor(){
        if( Elementor\Plugin::$instance->editor->is_edit_mode() ){
            wc()->frontend_includes();
    
            if(empty(WC()->cart->cart_contents)) {
        
                WC()->session = new \WC_Session_Handler();
                WC()->session->init();
                WC()->customer = new \WC_Customer(get_current_user_id(), true);
                WC()->cart = new \WC_Cart();
        
            }
        
            WC()->cart->calculate_totals();
        }
    }

    public function get_assigned_template( $name ){
        $template_settings = get_option('woolentor_woo_template_tabs');
        $template_id = !empty($template_settings[$name]) ? $template_settings[$name] : '';
    
        return $template_id;
    }
}

WooLentor_Checkout_Page::instance();