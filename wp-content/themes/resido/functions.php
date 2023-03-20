<?php
defined( 'RESIDO_THEME_URI' ) or define( 'RESIDO_THEME_URI', get_template_directory_uri() );
define( 'RESIDO_THEME_DRI', get_template_directory() );
define( 'RESIDO_IMG_URL', RESIDO_THEME_URI . '/assets/images/' );
define( 'RESIDO_CSS_URL', RESIDO_THEME_URI . '/assets/css/' );
define( 'RESIDO_JS_URL', RESIDO_THEME_URI . '/assets/js/' );
define( 'RESIDO_FRAMEWORK_DRI', RESIDO_THEME_DRI . '/framework/' );

require_once RESIDO_FRAMEWORK_DRI . 'styles/index.php';
require_once RESIDO_FRAMEWORK_DRI . 'styles/daynamic-style.php';
require_once RESIDO_FRAMEWORK_DRI . 'scripts/index.php';
require_once RESIDO_FRAMEWORK_DRI . 'redux/redux-config.php';
require_once RESIDO_FRAMEWORK_DRI . 'plugin-list.php';
require_once RESIDO_FRAMEWORK_DRI . 'tgm/class-tgm-plugin-activation.php';
require_once RESIDO_FRAMEWORK_DRI . 'tgm/config-tgm.php';
require_once RESIDO_FRAMEWORK_DRI . 'dashboard/class-dashboard.php';
require_once RESIDO_FRAMEWORK_DRI . 'classes/resido-actions.php';
require_once RESIDO_FRAMEWORK_DRI . 'classes/resido-act.php';


if ( ! function_exists( 'resido_options' ) ) :
	function resido_options() {
		global $resido_options;
		return $resido_options;
	}
endif;

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Theme option compatibility.
 */
if ( ! function_exists( 'resido_get_options' ) ) :
	function resido_get_options( $key ) {
		global $resido_options;
		$opt_pref = 'resido_';
		if ( empty( $resido_options ) ) {
			$resido_options = get_option( $opt_pref . 'options' );
		}
		$index = $opt_pref . $key;
		if ( ! isset( $resido_options[ $index ] ) ) {
			return false;
		}
		return $resido_options[ $index ];
	}
endif;

function sds_elementor_library() {
	$pageslist = get_posts(
		array(
			'post_type'      => 'elementor_library',
			'posts_per_page' => -1,
		)
	);
	$pagearray = array();
	if ( ! empty( $pageslist ) ) {
		foreach ( $pageslist as $page ) {
			$pagearray[ $page->ID ] = $page->post_title;
		}
	}
	return $pagearray;
}

if ( ! function_exists( 'resido_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function resido_setup() {
		/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on love us, use a find and replace
		* to change 'resido' to the name of your theme in all the template files.
		*/
		load_theme_textdomain( 'resido', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
		add_theme_support( 'title-tag' );

		/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary', 'resido' ),
			)
		);

		function resido_upload_mimes( $existing_mimes ) {
			$existing_mimes['webp'] = 'image/webp';
			return $existing_mimes;
		}
		add_filter( 'mime_types', 'resido_upload_mimes' );

		/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'resido_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		add_image_size( 'post-grid-thumbnail', 420, 300, true );
	}
endif;
add_action( 'after_setup_theme', 'resido_setup' );

function resido_google_font() {
	 $protocol  = is_ssl() ? 'https' : 'http';
	$display    = 'swap';
	$variants   = ':wght@300;400;500;600;700;800';
	$query_args = array(
		'family'  => 'Jost|Muli' . $variants,
		'family'  => 'Jost' . $variants . '&family=Muli' . $variants,
		'display' => $display,
	);
	$font_url   = add_query_arg( $query_args, $protocol . '://fonts.googleapis.com/css2' );
	wp_enqueue_style( 'resido-google-fonts', $font_url, array(), null );
}
add_action( 'init', 'resido_google_font' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function resido_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Blog Sidebar', 'resido' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'resido' ),
			'before_widget' => '<div id="%1$s" class="single-widgets %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<div class="sidebar-title"><h4>',
			'after_title'   => '</h4></div>',
		)
	);
}
add_action( 'widgets_init', 'resido_widgets_init' );


function resido_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'resido_content_width', 640 );
}
add_action( 'after_setup_theme', 'resido_content_width', 0 );


/**
 * Enqueue comment_reply.
 */
function resido_comment_reply() {
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'resido_comment_reply' );

/**
 * Blog Layout query_vars func.
 */

function resido_add_query_vars_filter( $vars ) {
	$vars[] = 'blog_layout';
	$vars[] = 'header_layout';
	return $vars;
}
add_filter( 'query_vars', 'resido_add_query_vars_filter' );

/**
 * Proper ob_end_flush() for all levels
 *
 * This replaces the WordPress `wp_ob_end_flush_all()` function
 * with a replacement that doesn't cause PHP notices.
 */
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
add_action(
	'shutdown',
	function() {
		while ( @ob_end_flush() );
	}
);



if ( ! function_exists( 'resido_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */

	function resido_register_elementor_locations( $elementor_theme_manager ) {
		$hook_result = apply_filters_deprecated( 'resido_theme_register_elementor_locations', array( true ), '2.0', 'resido_register_elementor_locations' );
		if ( apply_filters( 'resido_register_elementor_locations', $hook_result ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}

add_action( 'elementor/theme/register_locations', 'resido_register_elementor_locations' );

remove_action( 'clasify_classified_plugin_sectionthumbnail', 'listing_thumbnail', 10, 1 );

function resido_special_listing(){
	global $post;
	$author_id = $post->post_author;
	$listing_user = get_the_author_meta($author_id);
	$listing_user_email = get_the_author_meta( 'email', $author_id );
	
	$package_args  = array(
		'post_type'      => 'cl_payment',
		'posts_per_page' => 1,
		'meta_query'     => array(
			array(
				'key'   => '_cl_payment_user_email',
				'value' => $listing_user_email,
			),
		),
	);

	
	$package_name = '';
	$package_price = '';
	$plan_type = '';
	$package_query = new \WP_Query( $package_args );
	if ( $package_query->posts ) {
		foreach ( $package_query->posts as $key => $package ) {
			$package_data = get_post_meta( $package->ID, '_cl_payment_meta', true );
			$package_id   = $package_data['cart_details'][0]['id'];
			$package_price   = $package_data['cart_details'][0]['price'];
			$package_name   = $package_data['cart_details'][0]['name'];

			$plan_type = get_post_meta($package_id,'resido_plan_type',true);
		}
	}

	$woocommerce_active = cl_admin_get_option( 'woocommerce_active' ) != 1 ? false : true;
	if($woocommerce_active == '1' && class_exists( 'WooCommerce' )){
		$customer_orders = wc_get_orders( array(
			'meta_key' => '_customer_user',
			'meta_value' => $author_id,
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
			$package_start = $woo_order->get_date_completed();
			
			$product_id = '';
			$product_name = '';
			foreach ($items as $item) {
				$package_id = $item->get_product_id();
				$package_name = $item->get_name();
				$product = wc_get_product( $package_id );
				$package_price = $product->get_price();
				$plan_type = get_post_meta($package_id,'resido_plan_type',true);
			}
		}
	}
	


	$special_listing_class = array();
	if($plan_type == 'standard-pln'){
		$special_listing_class['package_class'] = 'blue_listing';
		$special_listing_class['package_badge'] = '';
	}elseif($plan_type == 'platinum-pln'){
		$special_listing_class['package_class'] = 'orange_listing';
		$special_listing_class['package_badge'] = '';
	}
	$special_listing_class = apply_filters( 'resido_special_listing_hook', $special_listing_class );
	return $special_listing_class;
}
