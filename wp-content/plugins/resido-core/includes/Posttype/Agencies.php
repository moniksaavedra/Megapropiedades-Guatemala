<?php
namespace Resido\Helper\Posttype;

class Agencies {

	/**
	 * Initialize the class
	 */
	function __construct() {
		// Register the post type
		add_action( 'init', array( $this, 'agencies' ), 1 );
		add_filter( 'rwmb_meta_boxes', array( $this, 'agencies_metabox' ) );
	}

	public function agencies() {
		$labels = array(
			'name'               => _x( 'Agencies', 'Post type general name', 'resido-core' ),
			'singular_name'      => _x( 'Agencies', 'Post type singular name', 'resido-core' ),
			'add_new'            => _x( 'Add New', 'resido-core' ),
			'add_new_item'       => __( 'Add New Agencies', 'resido-core' ),
			'edit_item'          => __( 'Edit Agencies', 'resido-core' ),
			'new_item'           => __( 'New Agencies', 'resido-core' ),
			'all_items'          => __( 'Agencies', 'resido-core' ),
			'view_item'          => __( 'View Agencies', 'resido-core' ),
			'search_items'       => __( 'Search Agencies', 'resido-core' ),
			'not_found'          => __( 'No products found', 'resido-core' ),
			'not_found_in_trash' => __( 'No products found in the Trash' ),
			'parent_item_colon'  => '',
			'menu_name'          => _x( 'Agencies', 'resido-core' ),
		);

		$args         = array(
			'labels'        => $labels,
			'menu_icon'     => 'dashicons-money-alt',
			'description'   => 'Holds our Agencies specific data',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author' ),
			'show_in_menu'  => 'edit.php?post_type=cl_cpt',
			'rewrite'       => array(
				'slug'       => 'agencies',
				'with_front' => false,
				'feeds'      => true,
			),
			'has_archive'   => 'agencies',
			'hierarchical'  => false,
		);
		$agency_onoff = resido_get_options( 'agency_onoff' );
		if ( $agency_onoff == '1' ) {
			register_post_type( 'listing_agencies', $args );
		}
	}



	function agencies_metabox( $meta_boxes ) {
		$prefix = 'resido_agencies_';

		// Agency Meta

		$meta_boxes[] = array(
			'id'        => 'framework-meta-box-resido-agency-details',
			'title'     => esc_html__( 'Agency Details', 'resido-core' ),
			'pages'     => array(
				'listing_agencies',
			),
			'context'   => 'normal',
			'priority'  => 'high',
			'tab_style' => 'left',
			'fields'    => array(
				// Address field.
				array(
					'id'      => $prefix . 'agency_address',
					'name'    => esc_html__( 'Address', 'resido-core' ),
					'type'    => 'text',
					'std'     => '3599 Huntz Lane',
					'columns' => 4,
				),
				// Cell field.
				array(
					'id'      => $prefix . 'agency_cell',
					'name'    => esc_html__( 'Cell', 'resido-core' ),
					'type'    => 'text',
					'std'     => '91 123 456 7859',
					'columns' => 4,
				),
				// Email field.
				array(
					'id'      => $prefix . 'agency_email',
					'name'    => esc_html__( 'Email', 'resido-core' ),
					'type'    => 'text',
					'std'     => 'email@email.com',
					'columns' => 4,
				),
			),
		);

		$meta_boxes[] = array(
			'id'        => 'framework-meta-box-resido-social-info',
			'title'     => esc_html__( 'Social Information', 'resido-core' ),
			'pages'     => array(
				'listing_agencies',
			),
			'context'   => 'normal',
			'priority'  => 'high',
			'tab_style' => 'left',
			'fields'    => array(
				// Social Fields
				array(
					'id'      => $prefix . 'agency_social',
					'type'    => 'textarea',
					'columns' => 12,
				),
			),
		);

		$meta_boxes[] = array(
			'id'        => 'framework-meta-box-resido-agency',
			'title'     => esc_html__( 'Agency Information', 'resido-core' ),
			'pages'     => array(
				'listing_agencies',
			),
			'context'   => 'normal',
			'priority'  => 'high',
			'tab_style' => 'left',
			'fields'    => array(
				// Agency Information.
				array(
					'id'      => $prefix . 'agency_information',
					'type'    => 'textarea',
					'columns' => 12,
				),
			),
		);
		return $meta_boxes;
	}
}
