<?php
/**
 * ReduxFramework Barebones Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */
if ( ! class_exists( 'Redux' ) ) {
	return;
}
// This is your option name where all the Redux data is stored.
$opt_prefix = 'resido_';
$opt_name   = 'resido_options';
$args       = array();

Redux::setArgs( $opt_name, $args );
Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Subscription Option', 'resido-core' ),
		'id'               => 'subscription_option',
		'customizer_width' => '400px',
		'priority'         => '10',
		'icon'             => 'el el-cog-alt',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'subscription_on_off',
				'type'    => 'switch',
				'title'   => esc_html__( 'Subscription', 'resido-core' ),
				'default' => true,
			),
		),
	)
);
Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Listing Single', 'resido-core' ),
		'id'               => 'single_listing',
		'customizer_width' => '400px',
		'priority'         => '10',
		'icon'             => 'el el-cog-alt',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'listing_single_layout',
				'type'    => 'select',
				'title'   => esc_html__( 'Listing Single Layout', 'resido-core' ),
				'options' => array(
					'1' => 'Layout 1',
					'2' => 'Layout 2',
					'3' => 'Layout 3',
				),
				'default' => '1',
			),
		),
	)
);

Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Listing Archive', 'resido-core' ),
		'id'               => 'listing_archive',
		'customizer_width' => '400px',
		'priority'         => '10',
		'icon'             => 'el el-cog-alt',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'ar_top_breadcrumb',
				'type'    => 'switch',
				'title'   => esc_html__( 'Breadcrumb', 'resido-core' ),
				'default' => true,
			),
			array(
				'required' => array(
					array( $opt_prefix . 'ar_top_breadcrumb', '=', '1' ),
				),
				'id'       => $opt_prefix . 'archive_page_title',
				'type'     => 'text',
				'title'    => esc_html__( 'Title', 'resido-core' ),
				'default'  => esc_html__( 'Property List', 'resido-core' ),
			),
			array(
				'required' => array(
					array( $opt_prefix . 'ar_top_breadcrumb', '=', '1' ),
				),
				'id'       => $opt_prefix . 'archive_page_subtitle',
				'type'     => 'text',
				'title'    => esc_html__( 'Sub Title', 'resido-core' ),
				'default'  => esc_html__( 'Property List With Sidebar', 'resido-core' ),
			),
		),
	)
);

Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Agent & Agency', 'resido-core' ),
		'id'               => 'listing_agentnagency',
		'customizer_width' => '400px',
		'priority'         => '10',
		'icon'             => 'el el-torso',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'agent_onoff',
				'type'    => 'switch',
				'title'   => esc_html__( 'Agent On/Off', 'resido-core' ),
				'default' => true,
			),
			array(
				'id'      => $opt_prefix . 'agent_breadcrumb',
				'type'    => 'switch',
				'title'   => esc_html__( 'Agent Breadcrumb', 'resido-core' ),
				'default' => true,
			),
			array(
				'required' => array(
					array( $opt_prefix . 'agent_breadcrumb', '=', '1' ),
				),
				'id'       => $opt_prefix . 'agent_archive_page_title',
				'type'     => 'text',
				'title'    => esc_html__( 'Agent Archive Page Title', 'resido-core' ),
				'default'  => esc_html__( 'All Agents', 'resido-core' ),
			),
			array(
				'required' => array(
					array( $opt_prefix . 'agent_breadcrumb', '=', '1' ),
				),
				'id'       => $opt_prefix . 'agent_archive_page_subtitle',
				'type'     => 'text',
				'title'    => esc_html__( 'Sub Title', 'resido-core' ),
				'default'  => esc_html__( 'Lists of our all expert agents', 'resido-core' ),
			),
			array(
				'id'      => $opt_prefix . 'agency_onoff',
				'type'    => 'switch',
				'title'   => esc_html__( 'Agency On/Off', 'resido-core' ),
				'default' => true,
			),
			array(
				'id'      => $opt_prefix . 'agency_breadcrumb',
				'type'    => 'switch',
				'title'   => esc_html__( 'Agency Breadcrumb', 'resido-core' ),
				'default' => true,
			),
			array(
				'required' => array(
					array( $opt_prefix . 'agency_breadcrumb', '=', '1' ),
				),
				'id'       => $opt_prefix . 'agency_archive_page_title',
				'type'     => 'text',
				'title'    => esc_html__( 'Agency Archive Page Title', 'resido-core' ),
				'default'  => esc_html__( 'All Agency', 'resido-core' ),
			),
			array(
				'required' => array(
					array( $opt_prefix . 'agency_breadcrumb', '=', '1' ),
				),
				'id'       => $opt_prefix . 'agency_archive_page_subtitle',
				'type'     => 'text',
				'title'    => esc_html__( 'Sub Title', 'resido-core' ),
				'default'  => esc_html__( 'Lists of our all Popular agencies', 'resido-core' ),
			),
		),
	)
);
