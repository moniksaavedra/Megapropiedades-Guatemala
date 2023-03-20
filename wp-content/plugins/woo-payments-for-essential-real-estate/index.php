<?php
/**
 * Plugin Name: Woo Payments for Essential Real Estate 
 * Plugin URI: https://smartdatasoft.com/
 * Description: Support paying for a cart with the payment system provided by WooCommerce
 * Author: SmartDataSoft
 * Version: 1.0
 * Author URI: https://smartdatasoft.com/
 * Tested up to: 6.0
 * WC tested up to: 6.9.4
 * Text Domain: woocommerce-payments-for-listings
 * Domain Path: /languages/
 */

if ( ! class_exists( 'WooCommerce_Payments_for_Listings' ) ) {

	/**
	 * Class WooCommerce_Payments_for_Listings
	 */
	class WooCommerce_Payments_for_Listings {

		/**
		 * @var null
		 */
		protected static $_instance = null;

		/**
		 * @var bool
		 */
		protected static $_wc_loaded = false;

		/**
		 * WooCommerce_Payments_for_Listings constructor.
		 */
		public function __construct() {

			$this->_defines();
		
			$woocommerce_active = cl_admin_get_option( 'woocommerce_active' ) != 1 ? false : true;
 			if($woocommerce_active == '1'){

			require_once "includes/functions.php";
		
				$this->_includes();
				// define plugin enable

				add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

				// woommerce currency
			//	add_filter( 'cl_currency', array( $this, 'woocommerce_currency' ), 50 );
				add_filter( 'cl_currency_symbol', array( $this, 'woocommerce_currency_symbol' ), 50, 2 );
			//	add_filter( 'cl_format_amount', array( $this, 'woocommerce_price_format' ), 50, 3 );

				add_action( 'template_redirect', array( $this, 'template_redirect' ), 50 );

				// add woo cart item
				add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item' ), 10, 2 );

				add_filter( 'woocommerce_product_class', array( $this, 'product_class' ), 10, 4 );
	
				add_filter( 'woocommerce_get_cart_item_from_session', array(
					$this,
					'get_cart_item_from_session'
				), 10, 3 );

				// override woo mail templates
				add_filter( 'woocommerce_locate_template', array( $this, 'woo_booking_mail_template' ), 10, 3 );
			}

		}

		/**
		 * Check plugin Woo activated.
		 */
		public static function check_woo_activated(): bool {
			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) || ! is_plugin_active( 'essential-wp-real-estate/index.php' ) ) {
				add_action( 'admin_notices', array( __CLASS__, 'show_note_errors_install_plugin_woo' ) );

				deactivate_plugins( plugin_basename( __FILE__ ) );

				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}

				return false;
			}

			return true;
		}


		public static function show_note_errors_install_plugin_woo() {
			?>
            <div class="notice notice-error">
                <p><?php echo 'Please active plugin <strong>Woocomerce</strong> and <strong>Essential WP Real Estate</strong> before active plugin <strong>Woo Payments for Essential Real Estate</strong>'; ?></p>
            </div>
			<?php
		}

		/**
		 * Override woo mail templates.
		 *
		 * @param $template
		 * @param $template_name
		 * @param $template_path
		 *
		 * @return string
		 * @since 2.0
		 *
		 */
		public function woo_booking_mail_template( $template, $template_name, $template_path ) {
			global $woocommerce;
	
			$_template = $template;
			if ( ! $template_path ) {
				$template_path = $woocommerce->template_url;
			}

			$plugin_path = WPFL_WOO_PAYMENT_ABSPATH . '/templates/woocommerce/';
			// Look within passed path within the theme - this is priority
			$template = locate_template( array( $template_path . $template_name, $template_name ) );

			// Modification: Get the template from this plugin, if it exists
			if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
				$template = $plugin_path . $template_name;
			}

			// Use default template
			if ( ! $template ) {
				$template = $_template;
			}

			// Return what we found
			return $template;
			
		}


		/**
		 * @param $session_data
		 * @param $values
		 * @param $key
		 *
		 * @return mixed
		 */
		public function get_cart_item_from_session( $session_data, $values, $key ) {
			$session_data['data']->set_props( $values );

			return $session_data;
		}


		// woo product class process
		function product_class( $classname, $product_type, $post_type, $product_id ) {
			$classname = 'CL_WC_Product_Listing';
		
			return $classname;
		}


		/**
		 * Add product class param.
		 *
		 * @param $cart_item
		 * @param $cart_id
		 *
		 * @return mixed
		 */
		function add_cart_item( $cart_item, $cart_id ) {
			$post_type = get_post_type( $cart_item['data']->get_id() );

				$cart_item['data']->set_props(
					array(
						'product_id'     => $cart_item['product_id'],
						'woo_cart_id'    => $cart_id
					)
				);
	

			return $cart_item;
		}


		/**
		 * @return null|WooCommerce_Payments_for_Listings
		 */
		public static function instance() {
			if ( ! self::$_instance ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Load function.
		 */
		public static function load() {

			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			if ( ! self::check_woo_activated() ) {
				return;
			}

			WooCommerce_Payments_for_Listings::instance();

		}

		/**
		 * Frontend scripts.
		 */
		public function frontend_scripts() {
			wp_enqueue_script( 'wp_custom_cart', WPFL_PLUGIN_URL . 'assets/js/cart.js', array( 'jquery' ),time(),true );
			wp_localize_script(
				'wp_custom_cart',
				'ajax_object',
				array(
					'ajax_nonce_listingcart' => wp_create_nonce( 'listingcart' ),
					'ajax_url'               => esc_url( admin_url( 'admin-ajax.php' ) ),
				)
			);
			wp_enqueue_style( 'wpfl-style', WPFL_PLUGIN_URL . 'assets/css/wpfl.css' );
		}

		/**
		 * Define constants
		 */
		private function _defines() {
			define( 'WPFL_WOO_PAYMENT_ABSPATH', plugin_dir_path( __FILE__ ) );
			define( 'WPFL_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
			define( 'WPFL_PLUGIN_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
		}

		/**
		 * Including library files
		 */
		private function _includes() {

			require_once "includes/class-wc-product-listing.php";
			require_once "includes/helper-hooks.php";	
			
		}

		/**
		 * return currency of woocommerce setting
		 *
		 * @param $currency
		 *
		 * @return string
		 */
		public function woocommerce_currency( $currency ) {
			return get_woocommerce_currency();
		}

		/**
		 * Return currency symbol of woocommerce setting.
		 *
		 * @param $symbol
		 * @param $currency
		 *
		 * @return string
		 */
		public function woocommerce_currency_symbol( $symbol, $currency ) {	
			return get_woocommerce_currency_symbol();
		}

		/**
		 * Get price within currency format using woocommerce setting.
		 *
		 * @param $price_format
		 * @param $price
		 * @param $with_currency
		 *
		 * @return string
		 */
		public function woocommerce_price_format( $price_format, $price, $with_currency ) {
			return wc_price( $price );
		}

		/**
		 * Redirect hotel cart, checkout to woo page
		 */
		public function template_redirect() {
			global $post;
			if ( ! $post ) {
				return;
			}

			$cart = get_page_by_title('Cart Page');
			$checkout = get_page_by_title('Checkout Page');

			if ( $post->ID == $cart->ID ) {
				wp_redirect( wc_get_cart_url() );
				exit();
			} else if ( $post->ID == $checkout->ID ) {
				wp_redirect( wc_get_checkout_url() );
				exit();
			}
		}


	}
}

add_action( 'plugins_loaded', array( 'WooCommerce_Payments_for_Listings', 'load' ) );



