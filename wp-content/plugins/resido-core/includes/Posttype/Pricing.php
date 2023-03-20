<?php
namespace Resido\Helper\Posttype;

class Pricing {

	/**
	 * Initialize the class
	 */
	function __construct() {
		// Register the post type
		add_action( 'init', array( $this, 'pricing_plan' ), 0 );
		add_filter( 'rwmb_meta_boxes', array( $this, 'pricing_metabox' ) );
	}

	public function pricing_plan() {
		$labels = array(
			'name'               => _x( 'Pricing Plan', 'Post type general name', 'resido-core' ),
			'singular_name'      => _x( 'Pricing Plan', 'Post type singular name', 'resido-core' ),
			'add_new'            => _x( 'Add New', 'resido-core' ),
			'add_new_item'       => __( 'Add New Pricing Plan', 'resido-core' ),
			'edit_item'          => __( 'Edit Pricing Plan', 'resido-core' ),
			'new_item'           => __( 'New Pricing Plan', 'resido-core' ),
			'all_items'          => __( 'Pricing Plan', 'resido-core' ),
			'view_item'          => __( 'View Pricing Plan', 'resido-core' ),
			'search_items'       => __( 'Search Pricing Plan', 'resido-core' ),
			'not_found'          => __( 'No products found', 'resido-core' ),
			'not_found_in_trash' => __( 'No products found in the Trash' ),
			'parent_item_colon'  => '',
			'menu_name'          => _x( 'Pricing Plan', 'resido-core' ),
		);

		$args = array(
			'labels'        => $labels,
			'menu_icon'     => 'dashicons-money-alt',
			'description'   => 'Holds our Pricing Plan specific data',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'show_in_menu'  => 'edit.php?post_type=cl_cpt',
			'has_archive'   => true,
		);
		register_post_type( 'pricing_plan', $args );
	}

	function pricing_metabox( $meta_boxes ) {
		$prefix = 'resido_';

		$meta_boxes[] = array(
			'id'        => 'framework-meta-box-rlisting',
			'title'     => esc_html__( 'Pricing Plan', 'resido-core' ),
			'pages'     => array(
				'pricing_plan',
			),
			'context'   => 'after_title',
			'priority'  => 'high',
			'tab_style' => 'left',
			'fields'    => array(
				array(
					'id'       => $prefix . 'plan_type',
					'columns'  => 3,
					'name'     => esc_html__( 'Pricing Type', 'resido-core' ),
					'std'      => 'lni-layers',
					'type'     => 'select',
					'options'  => array(
						'basic-pln'    => 'Basic',
						'platinum-pln' => 'Platinum',
						'standard-pln' => 'Standard',
						'free-pln' => 'Free',
					),
					// Allow to select multiple value?
					'multiple' => false,
				),
				array(
					'id'      => 'wperesds_pricing',
					'columns' => 3,
					'name'    => esc_html__( 'Price', 'resido-core' ),
					'type'    => 'text',
				),
				array(
					'id'      => $prefix . 'plan_expire',
					'columns' => 3,
					'name'    => esc_html__( 'Duration', 'resido-core' ),
					'type'    => 'number',
					'desc'    => 'Numbers of Days Submission will valid.',
				),
				array(
					'id'          => $prefix . 'list_subn_limit',
					'columns'     => 4,
					'name'        => esc_html__( 'Listing Submission Limit', 'resido-core' ),
					'type'        => 'text',
					'placeholder' => 'Example 5 or unlimited',
					'desc'        => 'Numbers of listing who subscribe for this plan can submit. You can set "5" for how many listing number And for the unlimited listing just set "unlimited"',
				),
				array(
					'id'      => $prefix . 'pricing_custom_url',
					'columns' => 3,
					'name'    => esc_html__( 'Pricing Custom URL', 'resido-core' ),
					'type'    => 'text',
					'desc'    => 'If you want to get payment by another system then use it',
				),
				array(
					'id'      => $prefix . 'recomendad',
					'name'    => esc_html__( 'Recommended', 'resido-core' ),
					'type'    => 'checkbox',
					'columns' => 6,
				),

			),
		);
		$meta_boxes[] = array(
			'id'        => 'framework-meta-box-rlisting-expire',
			'title'     => esc_html__( 'Listing Expire', 'clasifico-listing' ),
			'pages'     => array(
				'subscription',
			),
			'context'   => 'normal',
			'priority'  => 'high',
			'tab_style' => 'left',
			'fields'    => array(
				array(
					'name'       => esc_html__( 'Set Expire Date', 'clasifico-listing' ),
					'id'         => 'subscription_expire',
					'type'       => 'datetime',
					// Datetime picker options.
					// For date options, see here http://api.jqueryui.com/datepicker
					// For time options, see here http://trentrichardson.com/examples/timepicker/
					'js_options' => array(
						'stepMinute'      => 15,
						'showTimepicker'  => true,
						'controlType'     => 'select',
						'showButtonPanel' => false,
						'oneLine'         => true,
					),
					// Display inline?
					'inline'     => false,
					// Save value as timestamp?
					'timestamp'  => false,
				),
			),
		);
		return $meta_boxes;
	}


}
