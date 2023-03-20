<?php
namespace Resido\Helper\Admin\Metabox;

class Metabox {

	/**
	 * Initialize the class
	 */
	function __construct() {
		// Register the post type
		add_filter( 'rwmb_meta_boxes', array( $this, 'resido_register_framework_post_meta_box' ) );
		if ( ! is_plugin_active( 'resido-listing/resido-listing.php' ) ) {
			add_filter( 'rwmb_meta_boxes', array( $this, 'resido_register_framework_listing_meta_box' ) );
		}

	}

	/**
	 * Register meta boxes
	 *
	 * Remember to change "your_prefix" to actual prefix in your project
	 *
	 * @return void
	 */
	function resido_register_framework_post_meta_box( $meta_boxes ) {

		global $wp_registered_sidebars;

		/**
		 * prefix of meta keys (optional)
		 * Use underscore (_) at the beginning to make keys hidden
		 * Alt.: You also can make prefix empty to disable it
		 */
		// Better has an underscore as last sign

		$sidebars = array();

		foreach ( $wp_registered_sidebars as $key => $value ) {
			$sidebars[ $key ] = $value['name'];
		}

		$opacities = array();
		for ( $o = 0.0, $n = 0; $o <= 1.0; $o += 0.1, $n++ ) {
			$opacities[ $n ] = $o;
		}
		$prefix     = 'resido_core';
		$posts_page = get_option( 'page_for_posts' );
		if ( ! isset( $_GET['post'] ) || intval( $_GET['post'] ) != $posts_page ) {
			$meta_boxes[] = array(
				'id'       => $prefix . '_page_wiget_meta_box',
				'title'    => esc_html__( 'Page Settings', 'resido' ),
				'pages'    => array(
					'page',
				),
				'context'  => 'normal',
				'priority' => 'core',
				'fields'   => array(
					array(
						'name'    => esc_html__( 'Show breadcrumb', 'resido' ),
						'id'      => "{$prefix}_show_breadcrumb",
						'type'    => 'radio',
						'desc'    => '',
						'std'     => 'on',
						'options' => array(
							'on'  => 'On',
							'off' => 'Off',
						),
					),
				),
			);
		}

		return $meta_boxes;
	}

	/**
	 * Register meta boxes
	 *
	 * Remember to change "your_prefix" to actual prefix in your project
	 *
	 * @return void
	 */
	function resido_register_framework_listing_meta_box( $meta_boxes ) {

		$prefix = 'resido_listing';

		$meta_boxes[] = array(
			'id'        => 'framework-meta-box-agent-contact',
			'title'     => esc_html__( 'Agent & Agency Contact', 'resido-core' ),
			'pages'     => array(
				'cl_cpt',
			),
			'context'   => 'normal',
			'priority'  => 'high',
			'tab_style' => 'left',
			'fields'    => array(
				array(
					'name'            => esc_html__( 'Select Agency', 'resido-core' ),
					'id'              => "{$prefix}_rlagencyinfo",
					'type'            => 'select_advanced',
					'options'         => $this->resido_get_all_agency(),
					'multiple'        => false,
					'placeholder'     => 'Select Agency',
					'select_all_none' => false,
					'columns'         => 2,
				),
				array(
					'name'            => esc_html__( 'Select Agent', 'resido-core' ),
					'id'              => "{$prefix}_rlagentinfo",
					'type'            => 'select_advanced',
					'options'         => $this->resido_get_all_agents(),
					'multiple'        => false,
					'placeholder'     => 'Select Agent',
					'select_all_none' => false,
					'columns'         => 2,
				),

			),
		);

		return $meta_boxes;
	}

	public function resido_get_all_agency() {
		$pageslist = get_posts(
			array(
				'post_type'      => 'listing_agencies',
				'posts_per_page' => -1,
			)
		);
		$pagearray = array();
		if ( ! empty( $pageslist ) ) {
			$pagearray['0'] = esc_html__( 'Select Agency' );
			foreach ( $pageslist as $page ) {
				$pagearray[ $page->ID ] = $page->post_title;
			}
		}
		return $pagearray;
	}
	public function resido_get_all_agents() {
		$pageslist = get_posts(
			array(
				'post_type'      => 'listing_agents',
				'posts_per_page' => -1,
			)
		);
		$pagearray = array();
		if ( ! empty( $pageslist ) ) {
			$pagearray['0'] = esc_html__( 'Select Agent' );
			foreach ( $pageslist as $page ) {
				$pagearray[ $page->ID ] = $page->post_title;
			}
		}
		return $pagearray;
	}

}
