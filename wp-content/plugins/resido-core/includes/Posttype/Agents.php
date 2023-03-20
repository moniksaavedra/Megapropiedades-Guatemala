<?php
namespace Resido\Helper\Posttype;

use \Resido\Helper\Admin\Metabox\Metabox;

class Agents {

	/**
	 * Initialize the class
	 */
	function __construct() {
		// Register the post type
		add_action( 'init', array( $this, 'agents' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'agents_metabox' ) );

	}

	public function agents() {
		$labels = array(
			'name'               => _x( 'Agents', 'Post type general name', 'resido-core' ),
			'singular_name'      => _x( 'Agents', 'Post type singular name', 'resido-core' ),
			'add_new'            => _x( 'Add New', 'resido-core' ),
			'add_new_item'       => __( 'Add New Agents', 'resido-core' ),
			'edit_item'          => __( 'Edit Agents', 'resido-core' ),
			'new_item'           => __( 'New Agents', 'resido-core' ),
			'all_items'          => __( 'Agents', 'resido-core' ),
			'view_item'          => __( 'View Agents', 'resido-core' ),
			'search_items'       => __( 'Search Agents', 'resido-core' ),
			'not_found'          => __( 'No products found', 'resido-core' ),
			'not_found_in_trash' => __( 'No products found in the Trash' ),
			'parent_item_colon'  => '',
			'menu_name'          => _x( 'Agents', 'resido-core' ),
		);

		$args        = array(
			'labels'        => $labels,
			'menu_icon'     => 'dashicons-money-alt',
			'description'   => 'Holds our Agents specific data',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author' ),
			'show_in_menu'  => 'edit.php?post_type=cl_cpt',
			'rewrite'       => array(
				'slug'       => 'agents',
				'with_front' => false,
				'feeds'      => true,
			),
			'has_archive'   => 'agents',
			'hierarchical'  => false,
		);
		$agent_onoff = resido_get_options( 'agent_onoff' );
		if ( $agent_onoff == '1' ) {
			register_post_type( 'listing_agents', $args );
		}
	}



	function agents_metabox( $meta_boxes ) {
		$prefix = 'resido_agents_';
		$object = new Metabox();

		$meta_boxes[] = array(
			'id'        => 'framework-meta-box-rlisting',
			'title'     => esc_html__( 'Agents', 'resido-core' ),
			'pages'     => array(
				'listing_agents',
			),
			'context'   => 'after_title',
			'priority'  => 'high',
			'tab_style' => 'left',
			'fields'    => array(
				array(
					'id'      => $prefix . 'agent_address',
					'name'    => esc_html__( 'Address', 'resido-core' ),
					'type'    => 'text',
					'std'     => '3599 Huntz Lane',
					'columns' => 3,
				),
				// Cell field.
				array(
					'id'      => $prefix . 'agent_cell',
					'name'    => esc_html__( 'Cell', 'resido-core' ),
					'type'    => 'text',
					'std'     => '+91 123 456 7859',
					'columns' => 3,
				),
				// Email field.
				array(
					'id'      => $prefix . 'agent_email',
					'name'    => esc_html__( 'Email', 'resido-core' ),
					'type'    => 'text',
					'std'     => 'email@email.com',
					'columns' => 3,
				),
				// Agency field.
				array(
					'name'            => esc_html__( 'Select Agency', 'resido-core' ),
					'id'              => $prefix . 'parent_agency',
					'type'            => 'select_advanced',
					'options'         => $object->resido_get_all_agency(),
					'multiple'        => false,
					'placeholder'     => 'Select Agency',
					'select_all_none' => false,
					'columns'         => 3,
				),
			),
		);
		$meta_boxes[] = array(
			'id'        => 'framework-meta-box-resido-social-info',
			'title'     => esc_html__( 'Social Information', 'resido-core' ),
			'pages'     => array(
				'listing_agents',
			),
			'context'   => 'normal',
			'priority'  => 'high',
			'tab_style' => 'left',
			'fields'    => array(
				// Social Fields
				array(
					'id'      => $prefix . 'agent_social',
					'type'    => 'textarea',
					'columns' => 12,
				),
			),
		);
		$meta_boxes[] = array(
			'id'        => 'framework-meta-box-resido-agent',
			'title'     => esc_html__( 'Agent Information', 'resido-core' ),
			'pages'     => array(
				'listing_agents',
			),
			'context'   => 'normal',
			'priority'  => 'high',
			'tab_style' => 'left',
			'fields'    => array(
				// Agent Information.
				array(
					'id'      => $prefix . 'agent_information',
					'type'    => 'textarea',
					'columns' => 12,
				),
			),
		);

		return $meta_boxes;
	}
}
