<?php
// Silence is golden.
function resido_post_thumbnail_image($size = 'full')
{
	$resido_featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'resido_galleries_home');
?>
	<picture>
		<source type="image/jpeg" srcset="<?php echo esc_url($resido_featured_image_url); ?>">
		<img src="<?php echo esc_url($resido_featured_image_url); ?>" alt="<?php esc_attr_e('Img', 'leganic'); ?>">
	</picture>
<?php
}
/**
 * Strip all the tags except allowed html tags
 *
 * The name is based on inline editing toolbar name
 *
 * @param  string $string
 * @return string
 */
function resido_kses_basic($string = '')
{
	return wp_kses($string, resido_get_allowed_html_tags('basic'));
}

/**
 * Strip all the tags except allowed html tags
 *
 * The name is based on inline editing toolbar name
 *
 * @param  string $string
 * @return string
 */
function resido_kses_intermediate($string = '')
{
	return wp_kses($string, resido_get_allowed_html_tags('intermediate'));
}

/**
 * search product title
 *
 * @param string $searchKey
 *  @return string
 */

/**
 * get product permalink by id
 *
 * @param string $post_ID
 *  @return string
 */
if (!function_exists('woofilter_get_product_permalink')) {

	function woofilter_get_product_permalink()
	{
		if (isset($_POST['item_id'])) {
			$post_ID   = $_POST['item_id'];
			$permalink = get_permalink($post_ID);
			echo json_encode(array('post_permalink' => $permalink), JSON_PRETTY_PRINT);
			exit();
		}
	}
}
add_action('wp_ajax_woofilter_get_product_permalink', 'woofilter_get_product_permalink');
add_action('wp_ajax_nopriv_woofilter_get_product_permalink', 'woofilter_get_product_permalink');

function resido_get_allowed_html_tags($level = 'basic')
{
	$allowed_html = array(
		'b'      => array(),
		'i'      => array(),
		'u'      => array(),
		'em'     => array(),
		'br'     => array(),
		'abbr'   => array(
			'title' => array(),
		),
		'span'   => array(
			'class' => array(),
		),
		'strong' => array(),
	);

	if ($level === 'intermediate') {
		$allowed_html['a'] = array(
			'href'  => array(),
			'title' => array(),
			'class' => array(),
			'id'    => array(),
		);
	}

	return $allowed_html;
}

function videocafe_sc_heading($atts, $content = null)
{
	extract(
		shortcode_atts(
			array(
				'title_text' => '',
			),
			$atts,
			'heading'
		)
	);

	return '<h4 class="widget_title">' . $title_text . '</h4>';
}
add_shortcode('heading', 'videocafe_sc_heading');


add_shortcode('videocafe-category-checklist', 'videocafe_category_checklist');
function videocafe_category_checklist($atts)
{
	// process passed arguments or assign WP defaults
	$atts = shortcode_atts(
		array(
			'post_id'              => 0,
			'descendants_and_self' => 0,
			'selected_cats'        => false,
			'popular_cats'         => false,
			'checked_ontop'        => true,
		),
		$atts,
		'videocafe-category-checklist'
	);

	// string to bool conversion, so the bool params work as expected
	$atts['selected_cats'] = to_bool($atts['selected_cats']);
	$atts['popular_cats']  = to_bool($atts['popular_cats']);
	$atts['checked_ontop'] = to_bool($atts['checked_ontop']);

	// load template.php from admin, where wp_category_checklist() is defined
	include_once ABSPATH . '/wp-admin/includes/template.php';

	// generate the checklist
	ob_start();
?>
	<div class="categorydiv">
		<ul class="category-tabs">
			<div id="taxonomy-category" class="categorydiv">
				<div id="category-all" class="tabs-panel">
					<ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">
						<?php
						wp_category_checklist(
							$atts['post_id'],
							$atts['descendants_and_self'],
							$atts['selected_cats'],
							$atts['popular_cats'],
							null,
							$atts['checked_ontop']
						);
						?>
					</ul>
				</div>
			</div>
		</ul>
	</div>

<?php
	return ob_get_clean();
}
function to_bool($bool)
{
	return (is_bool($bool) ? $bool : (is_numeric($bool) ? ((bool) intval($bool)) : $bool !== 'false'));
}
if (!function_exists('resido_options')) :

	function resido_options()
	{
		global $resido_options;
		$opt_pref = 'resido_';
		if (empty($resido_options)) {
			$resido_options = get_option($opt_pref . 'options');
		}
		return $resido_options;
	}
endif;

// Elementor Header Function
if (!function_exists('resido_public_heading_control')) {
	function resido_public_heading_control($obj, $dflthdr)
	{
		$obj->start_controls_section(
			'public_header_control',
			array(
				'label' => __('Heading', 'resido-core'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$obj->add_control(
			'public_header_tag',
			array(
				'label'   => __('Tag', 'resido-core'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default' => $dflthdr,
			)
		);

		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'public_header_typography',
				'label'    => __('Typography', 'resido-core'),
				'selector' => '{{WRAPPER}} .typo-header-text',
			)
		);

		$obj->add_control(
			'public_header_color',
			array(
				'label'     => __('Color', 'resido-core'),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .typo-header-text' => 'color: {{VALUE}} !important',
				),
			)
		);

		$obj->end_controls_section();
	}
}

// Elementor Title Function
if (!function_exists('resido_public_title_control')) {
	function resido_public_title_control($obj, $dflthdr)
	{
		$obj->start_controls_section(
			'public_title_control',
			array(
				'label' => __('Title', 'resido-core'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$obj->add_control(
			'public_title_tag',
			array(
				'label'   => __('Tag', 'resido-core'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default' => $dflthdr,
			)
		);

		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'public_title_typography',
				'label'    => __('Typography', 'resido-core'),
				'selector' => '{{WRAPPER}} .typo-title-text',
			)
		);

		$obj->add_control(
			'public_title_color',
			array(
				'label'     => __('Color', 'resido-core'),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .typo-title-text' => 'color: {{VALUE}} !important',
				),
			)
		);

		$obj->end_controls_section();
	}
}

// Elementor Content Function
if (!function_exists('resido_public_content_control')) {
	function resido_public_content_control($obj, $dflthdr)
	{
		$obj->start_controls_section(
			'public_content_control',
			array(
				'label' => __('Content', 'resido-core'),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$obj->add_control(
			'public_content_tag',
			array(
				'label'   => __('Tag', 'resido-core'),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default' => $dflthdr,
			)
		);

		$obj->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'public_content_typography',
				'label'    => __('Typography', 'resido-core'),
				'selector' => '{{WRAPPER}} .typo-content-text',
			)
		);

		$obj->add_control(
			'public_content_color',
			array(
				'label'     => __('Color', 'resido-core'),
				'separator' => 'after',
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .typo-content-text' => 'color: {{VALUE}} !important',
				),
			)
		);

		$obj->end_controls_section();
	}
}




function resido_images_size()
{
	add_image_size('resido-blog-home', 370, 290, true);
	add_image_size('resido-blog-image-size', 370, 317, true);
	add_image_size('residofooter-recent-post-size', 90, 80, true);
	add_image_size('residowoo-image-size', 270, 250, true);
}

add_action('after_setup_theme', 'resido_images_size');

// css individual Load
add_filter('combine_vc_ele_css_pb_build_css_assets_css_path', 'resido_css_list');
function resido_css_list($product_css_path)
{
	$product_css_path = plugin_dir_path(__DIR__) . '/assets/elementor/css/';
	return $product_css_path;
}

add_filter('combine_vc_ele_css_pb_build_css_assets_css_url', 'resido_css_list_url');
function resido_css_list_url()
{
	$product_css_path = plugin_dir_url(__DIR__) . '/assets/elementor/css/';
	return $product_css_path;
}

add_filter('combine_vc_ele_css_pb_sc_list_array', 'resido_array_list');
function resido_array_list($product_css_array)
{
	$product_css_array = array(
		'smart_testimonials' => array(
			'css'      => array('smart_testimonials'),
			'js'       => array('smart_testimonials'),
			'external' => array(
				'css' => array(),
				'js'  => array(),
			),
		),

		'explore_property'   => array(
			'css'      => array(''),
			'js'       => array('explore_property_slide'),
			'external' => array(
				'css' => array(),
				'js'  => array(),
			),
		),

		'blog_post'          => array(
			'css'      => array('blog_sec'),
			'js'       => array(''),
			'external' => array(
				'css' => array(),
				'js'  => array(),
			),
		),

		'home_banner'        => array(
			'css'      => array('home_slider'),
			'js'       => array(''),
			'external' => array(
				'css' => array(),
				'js'  => array(),
			),
		),

	);
	return $product_css_array;
}

// css individual Load
