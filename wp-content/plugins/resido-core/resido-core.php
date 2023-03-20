<?php
/*
  Plugin Name: Resido Core
  Plugin URI: http://smartdatasoft.com/
  Description: Helping for the Resido theme.
  Author: SmartDataSoft
  Version: 3.4
  Author URI: http://smartdatasoft.com/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/breadcrumb-navxt/breadcrumb-navxt.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/mb-term-meta/mb-term-meta.php';
require_once __DIR__ . '/combine-vc-ele-css/combine-vc-ele-css.php';
require_once __DIR__ . '/page-option/page-option.php';

/**
 * The main plugin class
 */
final class Resido_Helper {


	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const version = '1.0';


	/**
	 * Plugin Version
	 *
	 * @since 1.2.0
	 * @var   string The plugin version.
	 */
	const VERSION = '1.2.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.2.0
	 * @var   string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.2.0
	 * @var   string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Constructor
	 *
	 * @since  1.0.0
	 * @access public
	 */

	/**
	 * Class construcotr
	 */
	private function __construct() {
		$this->define_constants();
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		if ( ! is_plugin_active( 'resido-listing/resido-listing.php' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'assets_scripts' ) );
		}
	}

	public function assets_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'listing-core-custom', plugins_url( '/assets/js/custom.js', __FILE__ ), array( 'jquery' ), time(), true );
		$ajax_var = array(
			'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
			'site_url' => esc_url( site_url() ),
		);
		wp_localize_script( 'listing-core-custom', 'ajax_obj', $ajax_var );
	}

	/**
	 * Initializes a singleton instance
	 *
	 * @return \Resido
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}


	/**
	 * Define the required plugin constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'RESIDO_CORE_VERSION', self::version );
		define( 'RESIDO_CORE_FILE', __FILE__ );
		define( 'RESIDO_CORE_PATH', __DIR__ );
		define( 'RESIDO_CORE_URL', plugin_dir_url( __FILE__ ) );
		define( 'RESIDO_CORE_ASSETS_DEPENDENCY_CSS', RESIDO_CORE_URL . '/assets/elementor/css/' );
		define( 'RESIDO_CORE_ASSETS', RESIDO_CORE_URL . 'assets' );
		$theme = wp_get_theme();
		define( 'RESIDO_THEME_VERSION_CORE', $theme->Version );
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init_plugin() {
		$this->checkElementor();
		load_plugin_textdomain( 'resido-core', false, basename( dirname( __FILE__ ) ) . '/languages' );
		new \Resido\Helper\Posttype();
		new \Resido\Helper\Hooks();
		// sidebar generator
		new \Resido\Helper\Sidebar_Generator();

		new \Resido\Helper\Widgets();
		if ( did_action( 'elementor/loaded' ) ) {
			new \Resido\Helper\Resido_Elementor();
		}

		if ( is_admin() ) {
			new \Resido\Helper\Admin();
		}
	}

	public function checkElementor() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = '<p>If you want to use Elementor Version of "<strong>resido</strong>" Theme, Its requires "<strong>Elementor</strong>" to be installed and activated.</p>';

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-hello-world' ),
			'<strong>' . esc_html__( 'Elementor Resido', 'elementor-hello-world' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-hello-world' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'resido-core' ),
			'<strong>' . esc_html__( 'Elementor Resido', 'resido-core' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'resido-core' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}


	/**
	 * Do stuff upon plugin activation
	 *
	 * @return void
	 */
	public function activate() {
		$installer = new Resido\Helper\Installer();
		$installer->run();
	}
}

/**
 * Initializes the main plugin
 *
 * @return \Resido
 */
function Resido() {
	 return Resido_Helper::init();
}

// kick-off the plugin
Resido();

/**
 * Passing Classes to Menu
 */
add_action(
	'wp_nav_menu_item_custom_fields',
	function ( $item_id, $item ) {
		if ( $item->menu_item_parent == '0' ) {
			$show_as_megamenu = get_post_meta( $item_id, '_show-as-megamenu', true ); ?>
		<p class="description-wide">
			<label for="megamenu-item-<?php echo $item_id; ?>"> <input type="checkbox" id="megamenu-item-<?php echo $item_id; ?>" name="megamenu-item[<?php echo $item_id; ?>]" <?php checked( $show_as_megamenu, true ); ?> /><?php _e( 'Mega menu', 'sds' ); ?>
			</label>
		</p>
			<?php
		}
	},
	10,
	2
);

add_action(
	'wp_update_nav_menu_item',
	function ( $menu_id, $menu_item_db_id ) {
		$button_value = ( isset( $_POST['megamenu-item'][ $menu_item_db_id ] ) && $_POST['megamenu-item'][ $menu_item_db_id ] == 'on' ) ? true : false;
		update_post_meta( $menu_item_db_id, '_show-as-megamenu', $button_value );
	},
	10,
	2
);

add_filter(
	'nav_menu_css_class',
	function ( $classes, $menu_item ) {
		if ( $menu_item->menu_item_parent == '0' ) {
			$show_as_megamenu = get_post_meta( $menu_item->ID, '_show-as-megamenu', true );
			if ( $show_as_megamenu ) {
				$classes[] = 'megamenu';
			}
		}
		return $classes;
	},
	10,
	2
);

// Post Nevigation
add_action( 'resido_navigation_post', 'resido_navigation_post_ready' );
function resido_navigation_post_ready( $post_id ) {
	$resido_prev_post = get_adjacent_post( false, '', true );
	$resido_next_post = get_adjacent_post( false, '', false );
	?>
	<div class="single-post-pagination">
		<?php if ( ! empty( $resido_prev_post ) ) { ?>
			<div class="prev-post">
				<a href="<?php echo esc_url( get_permalink( $resido_prev_post->ID ) ); ?>">
					<div class="title-with-link">
						<span class="intro"><?php echo esc_attr__( 'Prev Post', 'resido-core' ); ?></span>
						<h3 class="title"><?php echo esc_html( $resido_prev_post->post_title ); ?></h3>
					</div>
				</a>
			</div>
		<?php } ?>
		<div class="post-pagination-center-grid">
			<a href="#"><i class="ti-layout-grid3"></i></a>
		</div>
		<?php if ( ! empty( $resido_next_post ) ) { ?>
			<div class="next-post">
				<a href="<?php echo esc_url( get_permalink( $resido_next_post->ID ) ); ?>">
					<div class="title-with-link">
						<span class="intro"><?php echo esc_attr__( 'Next Post', 'resido-core' ); ?></span>
						<h3 class="title"><?php echo esc_html( $resido_next_post->post_title ); ?></h3>
					</div>
				</a>
			</div>
		<?php } ?>
	</div>
	<?php
}

// Custom Author Fields
function resido_user_social_links( $user_contact ) {
	$user_contact['phone']     = __( 'Phone', 'resido-core' );
	$user_contact['address']   = __( 'Address', 'resido-core' );
	$user_contact['city']      = __( 'City', 'resido-core' );
	$user_contact['state']     = __( 'State', 'resido-core' );
	$user_contact['zip']       = __( 'Zip', 'resido-core' );
	$user_contact['facebook']  = __( 'Facebook', 'resido-core' );
	$user_contact['twitter']   = __( 'Twitter', 'resido-core' );
	$user_contact['linkedin']  = __( 'Linkedin', 'resido-core' );
	$user_contact['instagram'] = __( 'Instagram', 'resido-core' );

	return $user_contact;
}
add_filter( 'user_contactmethods', 'resido_user_social_links' );


// Enqueue Style During Editing
add_action(
	'elementor/editor/before_enqueue_styles',
	function () {
		wp_enqueue_style( 'elementor-stylesheet', plugins_url() . '/resido-core/assets/elementor/stylesheets.css', true );
		// wp_enqueue_script( 'resido-core-script', plugins_url() . '/resido-core/assets/elementor/addons-script.js', array( 'jquery' ), time(), true );
	//	wp_enqueue_script( 'resido-core-script', plugins_url() . '/resido-core/assets/elementor/js/custom.js', array( 'jquery' ), time(), true );
	}
);

if ( ! function_exists( 'resido_blog_social' ) ) :
	function resido_blog_social() {
		?>
		<li><a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( get_permalink() ); ?>"><span class="fab fa-facebook-f"></span></a></li>
		<li><a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://twitter.com/home?status=<?php echo urlencode( get_the_title() ); ?>-<?php echo esc_url( get_permalink() ); ?>"><span class="fab fa-twitter"></span></a></li>
		<li><a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_url( get_permalink() ); ?>" target="_blank"><span class="fab fa-linkedin-in"></span></a></li>
		<li><a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://www.stumbleupon.com/submit?url=<?php echo esc_url( get_permalink() ); ?>&amp;text=<?php echo urlencode( get_the_title() ); ?>"><span class="fab fa-mix"></span></a></li>
		<?php
	}
endif;




use Essential\Restate\Front\Purchase\Payments\Clpayment;
use Essential\Restate\Admin\Settings\Payment\Subscriptionlist;

if ( ! is_plugin_active( 'resido-listing/resido-listing.php' ) ) {

	require_once RESIDO_CORE_PATH . '/template-loader.php';
	require_once RESIDO_CORE_PATH . '/search-query.php';
	require_once RESIDO_CORE_PATH . '/shortcode.php';
	require_once RESIDO_CORE_PATH . '/functions.php';
	require_once RESIDO_CORE_PATH . '/template-hooks.php';
	include RESIDO_CORE_PATH . '/redux.php';

	add_action( 'admin_init', 'resido_core_admin_init_func' );
	function resido_core_admin_init_func() {
		if ( class_exists( 'WPEssentialRealEstate' ) ) {
			require RESIDO_CORE_PATH . '/includes/Subscriptionlist.php';
		}
	}

	add_action( 'admin_menu', 'resido_listing_subscription_func' );
	function resido_listing_subscription_func() {
		add_submenu_page(
			'edit.php?post_type=cl_cpt',
			__( 'Subscription', 'resido-core' ),
			__( 'Subscription', 'resido-core' ),
			'manage_options',
			'cl-subscription-history',
			'cl_subscription_history_page'
		);
	}

	function cl_subscription_history_page() {

		$cl_payment = get_post_type_object( 'cl_payment' );

		if ( isset( $_GET['view'] ) && 'view-order-details' == $_GET['view'] ) {
			$payment_id = absint( $_GET['id'] );
			$payment    = new Clpayment( $payment_id );

			// Sanity check... fail if purchase ID is invalid
			$payment_exists = $payment->ID;
			if ( empty( $payment_exists ) ) {
				die( __( 'The specified ID does not belong to a payment. Please try again', 'resido-core' ) );
			}
			$args                   = array();
			$args['payment_id']     = $payment_id;
			$args['number']         = $payment->number;
			$args['payment_meta']   = $payment->get_meta();
			$args['transaction_id'] = esc_attr( $payment->transaction_id );
			$args['cart_items']     = $payment->cart_details;
			$args['user_id']        = $payment->user_id;
			$args['payment_date']   = strtotime( $payment->date );
			$args['unlimited']      = $payment->has_unlimited_listing;
			$args['user_info']      = cl_get_payment_meta_user_info( $payment_id );
			$args['address']        = $payment->address;
			$args['gateway']        = $payment->gateway;
			$args['currency_code']  = $payment->currency;
			$args['customer']       = new Customer( $payment->customer_id );
			$args['payment']        = $payment;

			cl_get_template( 'order_details.php', $args, '', WPERESDS_TEMPLATES_DIR . '/admin/' );
		} elseif ( isset( $_GET['page'] ) && 'cl-subscription-history' == $_GET['page'] ) {
			$payments_table = new Subscriptionlist();
			$payments_table->prepare_items();
			?>
		<div class="wrap">
			<h1><?php echo $cl_payment->labels->menu_name; ?></h1>
			<?php do_action( 'cl_payments_page_top' ); ?>
			<form id="cl-subscriptions-filter" method="get" action="<?php echo admin_url( 'edit.php?post_type=cl_cpt&page=cl-subscription-history' ); ?>">
				<input type="hidden" name="post_type" value="cl_cpt" />
				<input type="hidden" name="page" value="cl-subscription-history" />

				<?php $payments_table->display(); ?>
			</form>
			<?php do_action( 'cl_payments_page_bottom' ); ?>
		</div>
			<?php
		}
	}

	function cl_listing_view_hook_func() {
		echo '<div class="footer-flex"><a href="' . esc_url( get_permalink() ) . '" class="prt-view">' . esc_html__( 'View', 'wp-essential-real-estate' ) . '</a></div>';
	}
	add_filter( 'cl_listing_view_hook', 'cl_listing_view_hook_func' );

	function cl_listing_social_share_func() {
		?>
	<li class="social_share_list"><a href="JavaScript:Void(0);" class="btn btn-likes"><i class="fas fa-share"></i><?php echo esc_html__( 'Share', 'resido-core' ); ?></a>
		<?php
		$facebook  = 'http://www.facebook.com/sharer.php?u=' . get_the_permalink();
		$twitter   = 'http://twitter.com/home?status=' . get_the_title() . '  ' . get_the_permalink();
		$linked_in = 'http://linkedin.com/shareArticle?mini=true&url=' . get_the_permalink() . '&title=' . get_the_title();
		$telegram  = 'https://t.me/share/url?url=' . get_the_permalink() . '&text=' . get_the_title();
		$vk        = 'http://vk.com/share.php?url=' . get_the_permalink();
		?>
		<div class="social_share_panel">
			<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" class="cl-facebook"><i class="lni-facebook"></i></a>
			<a href="<?php echo esc_url( $twitter ); ?>" target="_blank" class="cl-twitter"><i class="lni-twitter"></i></a>
			<a href="<?php echo esc_url( $linked_in ); ?>" target="_blank" class="cl-linkedin"><i class="lni-linkedin"></i></a>
			<a href="whatsapp://send?text=<?php echo get_the_permalink(); ?>" target="_blank"><i class="lni-whatsapp"></i></a>
			<a href="<?php echo esc_url( $telegram ); ?>" target="_blank"><i class="lni-telegram"></i></a>
			<a href="<?php echo esc_url( $vk ); ?>" target="_blank"><i class="lni-vk"></i></a>
		</div>
	</li>
		<?php
	}
	add_filter( 'cl_listing_social_share', 'cl_listing_social_share_func' );

	function cl_listing_favourite_func() {
		?>
	<li>
		<?php
		global $current_user;
		if ( ! is_user_logged_in() ) {
			echo '<a href="JavaScript:Void(0);" data-bs-toggle="modal" data-bs-target="#login" class="btn btn-list live_single_2"><i class="far fa-heart"></i>' . __( 'Save', 'resido-core' ) . '</a>';
		} else {
			$user_meta = get_user_meta( $current_user->ID, '_favorite_posts' );
			if ( in_array( get_the_ID(), $user_meta ) ) {
				echo '<a href="javascript:void(0)" data-balloon-nofocus data-balloon-pos="up" aria-label="Save property" data-userid="' . esc_attr( $current_user->ID ) . '" data-postid="' . esc_attr( get_the_ID() ) . '" class="cl_favorite_item add-to-favorite prt_saveed_12lk btn btn-list" id="like_listing' . get_the_ID() . '"><i class="save_class_sdbr fas fa-heart"></i>' . __( 'Saved', 'resido-core' ) . '</a>';
			} else {
				echo '<a href="javascript:void(0)" data-balloon-nofocus data-balloon-pos="up" aria-label="Save property" data-userid="' . esc_attr( $current_user->ID ) . '" data-postid="' . esc_attr( get_the_ID() ) . '" class="add-to-favorite prt_saveed_12lk btn btn-list" id="like_listing' . get_the_ID() . '"><i class="save_class_sdbr far fa-heart"></i>' . __( 'Save', 'resido-core' ) . '</a>';
			}
		}
		?>
	</li>
		<?php
	}
	add_filter( 'cl_listing_favourite', 'cl_listing_favourite_func' );

	function cl_listing_compare_func() {
		?>
	<div class="compare_section">
		<a href="javascript:void(0)" data-postid="<?php echo esc_attr( get_the_ID() ); ?>" data-balloon-nofocus data-balloon-pos="up" aria-label="Compare property" class="compare-bt-single add-to-compare prt_saveed_12lk"><i class="ti-control-shuffle"></i><?php echo esc_html_e( 'Add to Compare', 'resido-core' ); ?></a>
	</div>
		<?php
	}
	add_filter( 'cl_listing_compare', 'cl_listing_compare_func' );

	function cl_listing_single_galary_hook_func() {
		return false;
	}
	add_filter( 'cl_listing_single_galary_hook', 'cl_listing_single_galary_hook_func', 10 );

	add_filter( 'cl_add_listing_form_check_package', 'resido_add_listing_form_before_filter_func' );
	function resido_add_listing_form_before_filter_func() {

		$subscription_on_off        = resido_get_options( 'subscription_on_off' );

		$current_user  = wp_get_current_user();
		$package_args  = array(
			'post_type'      => 'cl_payment',
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'   => '_cl_payment_user_email',
					'value' => $current_user->data->user_email,
				),
			),
		);
		$package_query = new \WP_Query( $package_args );
		if ( $package_query->posts ) {
			foreach ( $package_query->posts as $key => $post ) {
				$package_name = get_post_meta( $post->ID, '_cl_payment_meta', true );
				$package_id   = $package_name['cart_details'][0]['id'];
			}
		}

		$woocommerce_active = cl_admin_get_option( 'woocommerce_active' ) != 1 ? false : true;
		if($woocommerce_active == '1' && class_exists( 'WooCommerce' )){
			$customer_orders = wc_get_orders( array(
				'meta_key' => '_customer_user',
				'meta_value' => $current_user->ID,
				'post_status' => 'wc-completed',
				'numberposts' => 1
			) );

			if($customer_orders){
				foreach($customer_orders as $order ){
					$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
				}

				$woo_order = new WC_Order($order_id);
				$order_user = $woo_order->get_user();
				$items = $woo_order->get_items();
			
				$product_id = '';
				$product_name = '';
				foreach ($items as $item) {
					$package_id = $item->get_product_id();
					$product_name = $item->get_name();
				}

			}
		}

		if ( ! empty( $package_id ) && $subscription_on_off == 1 ) {
			$package_listing_count = get_post_meta( $package_id, 'resido_list_subn_limit', true );
			if ( $package_listing_count != 'unlimited' ) {
				if ( cl_total_active_listing_by_user() < $package_listing_count ) {
					return true;
				} else {
					?>
				<section class="gray-simple">
					<div class="container">
						<div class='cl-user-overview-listing-approve-notice text-center'>
							<p><?php esc_html_e( 'Your package limit is over.', 'resido-core' ); ?></p>
						</div>
					</div>
				</section>
					<?php
					return false;
				}
			} else {
				return true;
			}
		}elseif($subscription_on_off == '0' ){
			return true;
		} else {
			?>
	<section class="gray-simple mb-40">
		<div class="container">
			<div class='cl-user-overview-listing-approve-notice text-center'>
				<p><?php esc_html_e( 'Subscribe Package to add listing.', 'resido-core' ); ?></p>
			</div>
		</div>
	</section>
	<section class="pricing-section">
		<div class="container">
			<div class="row">
					<?php
						$args = array(
							'posts_per_page' => -1,
							'post_type'      => 'pricing_plan',
							'order'          => 'desc',
							'no_found_rows'  => true,
						);

						$query = new \WP_Query( $args );
						if ( $query->have_posts() ) :
							while ( $query->have_posts() ) :
								$query->the_post();

								$price      = get_post_meta( get_the_ID(), 'wperesds_pricing', true );
								$custom_url = get_post_meta( get_the_ID(), 'pricing_custom_url', true );
								$plan_type  = get_post_meta( get_the_ID(), 'resido_plan_type', true );
								if ( $plan_type == 'standard-pln' ) {
									$plan_type_cls = 'standard-pr';
								} elseif ( $plan_type == 'platinum-pln' ) {
									$plan_type_cls = 'platinum-pr';
								} else {
									$plan_type_cls = 'basic-pr';
								}
									?>
								<div class="col-md-4">
									<div class="pricing-wrap <?php echo esc_attr( $plan_type_cls ); ?>">
										<div class="pricing-header">
											<h4 class="pr-value"><sup><?php echo resido_currency_symbol(); ?></sup><?php echo esc_html( $price ); ?></h4>
											<h4 class="pr-title"><?php the_title(); ?></h4>
										</div>
										<div class="pricing-body">
											<?php the_content(); ?>
										</div>
										<?php
											if ( isset( $custom_url ) && ! empty( $custom_url ) ) {
												?>
											<div class="pricing-bottom">
												<a href="<?php echo esc_html( $custom_url ); ?>" class="btn-pricing"><?php echo esc_html__( 'Choose Plan','resido-core' ); ?></a>
											</div>
												<?php
											} else {
												?>
												<div class="pricing-bottom">
													<?php
														$cartactions = WPERECCP()->front->cart;
														$cartactions->append_cart_button(
															array(
																'price' => 'no',
																'post_id' => get_the_ID(),
																'class'      => 'btn btn-theme-light-2 rounded',
																'text'      => esc_html__( 'Choose Plan','resido-core' ),
															)
														);
													?>
												</div>
												<?php
											}
										?>
									</div>
								</div>
									<?php
						endwhile;
							wp_reset_postdata();
					endif;
						?>
		</div>
		</div>
	</section>
			<?php
		}

	}


}