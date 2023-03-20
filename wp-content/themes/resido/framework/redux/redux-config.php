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
/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */
$theme = wp_get_theme(); // For use with some settings. Not necessary.
$args  = array(
	// TYPICAL -> Change these values as you need/desire
	'opt_name'             => $opt_name,
	// This is where your data is stored in the database and also becomes your global variable name.
	'display_name'         => $theme->get( 'Name' ),
	// Name that appears at the top of your panel
	'display_version'      => $theme->get( 'Version' ),
	// Version that appears at the top of your panel
	'menu_type'            => 'menu',
	// Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
	'allow_sub_menu'       => true,
	// Show the sections below the admin menu item or not
	'menu_title'           => esc_html__( 'Resido Options', 'resido' ),
	'page_title'           => esc_html__( 'Resido Options', 'resido' ),
	// You will need to generate a Google API key to use this feature.
	// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
	'google_api_key'       => '',
	// Set it you want google fonts to update weekly. A google_api_key value is required.
	'google_update_weekly' => false,
	// Must be defined to add google fonts to the typography module
	'async_typography'     => true,
	// Use a asynchronous font on the front end or font string
	// 'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
	'admin_bar'            => true,
	// Show the panel pages on the admin bar
	'admin_bar_icon'       => 'dashicons-portfolio',
	// Choose an icon for the admin bar menu
	'admin_bar_priority'   => 50,
	// Choose an priority for the admin bar menu
	'global_variable'      => '',
	// Set a different name for your global variable other than the opt_name
	'dev_mode'             => false,
	// Show the time the page took to load, etc
	'update_notice'        => true,
	// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
	'customizer'           => true,
	// Enable basic customizer support
	// 'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
	// 'disable_save_warn' => true,                    // Disable the save warning when a user changes a field
	// OPTIONAL -> Give you extra features
	'page_priority'        => null,
	// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
	'page_parent'          => 'themes.php',
	// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	'page_permissions'     => 'manage_options',
	// Permissions needed to access the options panel.
	'menu_icon'            => '',
	// Specify a custom URL to an icon
	'last_tab'             => '',
	// Force your panel to always open to a specific tab (by id)
	'page_icon'            => 'icon-themes',
	// Icon displayed in the admin panel next to your menu_title
	'page_slug'            => '_options',
	// Page slug used to denote the panel
	'save_defaults'        => true,
	// On load save the defaults to DB before user clicks save or not
	'default_show'         => false,
	// If true, shows the default value next to each field that is not the default value.
	'default_mark'         => '',
	// What to print by the field's title if the value shown is default. Suggested: *
	'show_import_export'   => true,
	// Shows the Import/Export panel when not used as a field.
	// CAREFUL -> These options are for advanced use only
	'transient_time'       => 60 * MINUTE_IN_SECONDS,
	'output'               => true,
	// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
	'output_tag'           => true,
	// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
	// 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
	// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
	'database'             => '',
	// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
	'use_cdn'              => true,
	// If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
	// 'compiler'             => true,
);
Redux::setArgs( $opt_name, $args );
Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'General', 'resido' ),
		'id'               => 'resido_general',
		'customizer_width' => '400px',
		'icon'             => 'el el-cogs',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'preloader',
				'type'    => 'switch',
				'title'   => esc_html__( 'Preloader', 'resido' ),
				'default' => false,
			),
			array(
				'id'      => $opt_prefix . 'sticky_onoff',
				'type'    => 'switch',
				'title'   => esc_html__( 'Sticky Header', 'resido' ),
				'default' => false,
			),
			array(
				'id'      => $opt_prefix . 'back_to_top',
				'type'    => 'switch',
				'title'   => esc_html__( 'Back to top', 'resido' ),
				'default' => false,
			),
		),
	)
);
Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Color option', 'resido' ),
		'id'               => 'resido_color_option',
		'customizer_width' => '400px',
		'icon'             => 'el el-brush',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'color_option_status',
				'type'    => 'switch',
				'title'   => esc_html__( 'Status', 'resido' ),
				'default' => true,
			),
			array(
				'id'               => $opt_prefix . 'primary_color',
				'required'         => array( $opt_prefix . 'color_option_status', '=', array( '1' ) ),
				'type'             => 'color',
				'title'            => esc_html__( 'Primary Color', 'resido' ),
				'default'          => '#fd5332',
				'validate'         => 'color',
				'output_variables' => true,
			),
		),
	)
);
Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Typography', 'resido' ),
		'id'               => 'resido_typography',
		'customizer_width' => '400px',
		'icon'             => 'el el-font',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'typography_status',
				'type'    => 'switch',
				'title'   => esc_html__( 'Status', 'resido' ),
				'default' => false,
			),
			array(
				'id'          => $opt_prefix . 'h1_typography',
				'required'    => array( $opt_prefix . 'typography_status', '=', array( '1' ) ),
				'type'        => 'typography',
				'title'       => esc_html__( 'H1', 'resido' ),
				'google'      => true,
				'font-backup' => true,
				'output'      => array( 'h1' ),
				'units'       => 'px',
				'subtitle'    => esc_html__( 'Typography option for H1', 'resido' ),
			),
			array(
				'id'          => $opt_prefix . 'h2_typography',
				'required'    => array( $opt_prefix . 'typography_status', '=', array( '1' ) ),
				'type'        => 'typography',
				'title'       => esc_html__( 'H2', 'resido' ),
				'google'      => true,
				'font-backup' => true,
				'output'      => array( 'h2' ),
				'units'       => 'px',
				'subtitle'    => esc_html__( 'Typography option for H2', 'resido' ),
			),
			array(
				'id'          => $opt_prefix . 'h3_typography',
				'required'    => array( $opt_prefix . 'typography_status', '=', array( '1' ) ),
				'type'        => 'typography',
				'title'       => esc_html__( 'H3', 'resido' ),
				'google'      => true,
				'font-backup' => true,
				'output'      => array( 'h3' ),
				'units'       => 'px',
				'subtitle'    => esc_html__( 'Typography option for H3', 'resido' ),
			),
			array(
				'id'          => $opt_prefix . 'h4_typography',
				'required'    => array( $opt_prefix . 'typography_status', '=', array( '1' ) ),
				'type'        => 'typography',
				'title'       => esc_html__( 'H4', 'resido' ),
				'google'      => true,
				'font-backup' => true,
				'output'      => array( 'h4' ),
				'units'       => 'px',
				'subtitle'    => esc_html__( 'Typography option for H4', 'resido' ),
			),
			array(
				'id'          => $opt_prefix . 'h5_typography',
				'required'    => array( $opt_prefix . 'typography_status', '=', array( '1' ) ),
				'type'        => 'typography',
				'title'       => esc_html__( 'H5', 'resido' ),
				'google'      => true,
				'font-backup' => true,
				'output'      => array( 'h5' ),
				'units'       => 'px',
				'subtitle'    => esc_html__( 'Typography option for H5', 'resido' ),
			),
			array(
				'id'          => $opt_prefix . 'h6_typography',
				'required'    => array( $opt_prefix . 'typography_status', '=', array( '1' ) ),
				'type'        => 'typography',
				'title'       => esc_html__( 'H6', 'resido' ),
				'google'      => true,
				'font-backup' => true,
				'output'      => array( 'h6' ),
				'units'       => 'px',
				'subtitle'    => esc_html__( 'Typography option for H6', 'resido' ),
			),
			array(
				'id'          => $opt_prefix . 'p_typography',
				'required'    => array( $opt_prefix . 'typography_status', '=', array( '1' ) ),
				'type'        => 'typography',
				'title'       => esc_html__( 'P', 'resido' ),
				'google'      => true,
				'font-backup' => true,
				'output'      => array( 'p' ),
				'units'       => 'px',
				'subtitle'    => esc_html__( 'Typography option for P', 'resido' ),
			),
			array(
				'id'          => $opt_prefix . 'a_typography',
				'required'    => array( $opt_prefix . 'typography_status', '=', array( '1' ) ),
				'type'        => 'typography',
				'title'       => esc_html__( 'A', 'resido' ),
				'google'      => true,
				'font-backup' => true,
				'output'      => array( 'a' ),
				'units'       => 'px',
				'subtitle'    => esc_html__( 'Typography option for A', 'resido' ),
			),
			array(
				'id'          => $opt_prefix . 'span_typography',
				'required'    => array( $opt_prefix . 'typography_status', '=', array( '1' ) ),
				'type'        => 'typography',
				'title'       => esc_html__( 'Span', 'resido' ),
				'google'      => true,
				'font-backup' => true,
				'output'      => array( 'span' ),
				'units'       => 'px',
				'subtitle'    => esc_html__( 'Typography option for Span', 'resido' ),
			),
			array(
				'id'          => $opt_prefix . 'body_typography',
				'required'    => array( $opt_prefix . 'typography_status', '=', array( '1' ) ),
				'type'        => 'typography',
				'title'       => esc_html__( 'Body', 'resido' ),
				'google'      => true,
				'font-backup' => true,
				'output'      => array( 'body' ),
				'units'       => 'px',
				'subtitle'    => esc_html__( 'Typography option for Body', 'resido' ),
			),
		),
	)
);
Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Header', 'resido' ),
		'id'               => 'resido_header',
		'customizer_width' => '400px',
		'icon'             => 'el el-magic',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'header_type',
				'type'    => 'select',
				'title'   => esc_html__( 'Type', 'resido' ),
				'options' => array(
					'1' => esc_html__( 'Default', 'resido' ),
					'2' => esc_html__( 'Elementor', 'resido' ),
				),
				'default' => '1',
			),
			array(
				'id'       => $opt_prefix . 'header_default_style',
				'required' => array( $opt_prefix . 'header_type', '=', array( '1' ) ),
				'type'     => 'select',
				'title'    => esc_html__( 'Layout', 'resido' ),
				'options'  => array(
					'1' => esc_html__( 'Style 1', 'resido' ),
					'2' => esc_html__( 'Style 2', 'resido' ),
				),
				'default'  => '1',
			),
			array(
				'id'       => $opt_prefix . 'header_right_menu',
				'required' => array(
					array( $opt_prefix . 'header_type', '=', '1' ),
				),
				'type'     => 'switch',
				'title'    => esc_html__( 'Header Right', 'resido' ),
				'default'  => true,
			),
			array(
				'id'       => $opt_prefix . 'header_elementor_template',
				'required' => array( $opt_prefix . 'header_type', '=', array( '2' ) ),
				'type'     => 'select',
				'multi'    => false,
				'title'    => esc_html__( 'Elementor Template', 'resido' ),
				'options'  => sds_elementor_library(),
			),
			array(
				'id'       => $opt_prefix . 'header_transparent',
				'required' => array(
					array( $opt_prefix . 'header_type', '=', '1' ),
					array( $opt_prefix . 'header_default_style', '=', '1' ),
				),
				'type'     => 'switch',
				'title'    => esc_html__( 'Transparent Header', 'resido' ),
				'default'  => false,
			),
			array(
				'id'       => $opt_prefix . 'header_top_status',
				'required' => array(
					array( $opt_prefix . 'header_type', '=', '1' ),
					array( $opt_prefix . 'header_default_style', '=', '2' ),
				),
				'type'     => 'switch',
				'title'    => esc_html__( 'Header Top', 'resido' ),
				'default'  => false,
			),
			array(
				'required' => array(
					array( $opt_prefix . 'header_type', '=', '1' ),
					array( $opt_prefix . 'header_default_style', '=', '2' ),
				),
				'id'       => $opt_prefix . 'email_info',
				'type'     => 'text',
				'title'    => esc_html__( 'Email', 'resido' ),
				'default'  => esc_html__( 'needhelp@example.com', 'resido' ),
			),
			array(
				'required' => array(
					array( $opt_prefix . 'header_type', '=', '1' ),
					array( $opt_prefix . 'header_default_style', '=', '2' ),
				),
				'id'       => $opt_prefix . 'phone_info',
				'type'     => 'text',
				'title'    => esc_html__( 'Phone', 'resido' ),
				'default'  => esc_html__( '92 888 666 0000', 'resido' ),
			),
			array(
				'required' => array(
					array( $opt_prefix . 'header_type', '=', '1' ),
					array( $opt_prefix . 'header_default_style', '=', '2' ),
				),
				'id'       => $opt_prefix . 'social_information',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Social Info', 'resido' ),
			),
		),
	)
);
Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Breadcrumb', 'resido' ),
		'id'               => 'resido_breadcrumb',
		'customizer_width' => '400px',
		'icon'             => 'el el-magic',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'breadcrumb_status',
				'type'    => 'switch',
				'title'   => esc_html__( 'Status', 'resido' ),
				'default' => true,
			),
			array(
				'id'       => $opt_prefix . 'breadcrumb_list',
				'required' => array( $opt_prefix . 'breadcrumb_status', '=', array( '1' ) ),
				'type'     => 'switch',
				'title'    => esc_html__( 'Show Breadcrumb List', 'resido' ),
				'default'  => false,
			),
			array(
				'id'       => $opt_prefix . 'breadcrumb_subtitle',
				'required' => array( $opt_prefix . 'breadcrumb_status', '=', array( '1' ) ),
				'type'     => 'text',
				'title'    => esc_html__( 'Subtitle', 'resido' ),
				'default'  => esc_html__( 'See Our Latest Articles & News.', 'resido' ),
			),
			array(
				'id'       => $opt_prefix . 'breadcrumb_background',
				'required' => array( $opt_prefix . 'breadcrumb_status', '=', array( '1' ) ),
				'type'     => 'background',
				'title'    => esc_html__( 'Background', 'resido' ),
				'default'  => array(
					'background-color' => '#2540a2',
				),
				'output'   => array(
					'background' => '.page-title',
				),
			),
		),
	)
);
Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Blog', 'resido' ),
		'id'               => 'resido_blog',
		'customizer_width' => '400px',
		'icon'             => 'el el-magic',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'blog_layout',
				'type'    => 'select',
				'title'   => esc_html__( 'Layout', 'resido' ),
				'options' => array(
					'1' => esc_html__( 'List', 'resido' ),
					'2' => esc_html__( 'Grid', 'resido' ),
				),
				'default' => '1',
			),
			array(
				'id'      => $opt_prefix . 'blog_page_title',
				'type'    => 'text',
				'title'   => esc_html__( 'Archive Page Title', 'resido' ),
				'default' => esc_html__( 'Our Articles', 'resido' ),
			),
			array(
				'id'      => $opt_prefix . 'blog_page_single_title',
				'type'    => 'text',
				'title'   => esc_html__( 'Single Page Title', 'resido' ),
				'default' => esc_html__( 'Blog Details', 'resido' ),
			),
			array(
				'id'      => $opt_prefix . 'blog_title',
				'type'    => 'text',
				'title'   => esc_html__( 'Title', 'resido' ),
				'default' => esc_html__( 'Latest News', 'resido' ),
			),
			array(
				'id'      => $opt_prefix . 'blog_subtitle',
				'type'    => 'text',
				'title'   => esc_html__( 'Subtitle', 'resido' ),
				'default' => esc_html__( 'We post regulary most powerful articles for help and support.', 'resido' ),
			),
			array(
				'id'      => $opt_prefix . 'blog_social_share',
				'type'    => 'switch',
				'title'   => esc_html__( 'Social Share', 'resido' ),
				'default' => true,
			),
			array(
				'id'      => $opt_prefix . 'blog_author_box',
				'type'    => 'switch',
				'title'   => esc_html__( 'Author Box', 'resido' ),
				'default' => true,
			),
			array(
				'id'      => $opt_prefix . 'blog_post_nav',
				'type'    => 'switch',
				'title'   => esc_html__( 'Post Nevigation', 'resido' ),
				'default' => true,
			),
		),
	)
);
Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Footer', 'resido' ),
		'id'               => 'resido_footer',
		'customizer_width' => '400px',
		'icon'             => 'el el-magic',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'footer_elementor',
				'type'    => 'switch',
				'title'   => esc_html__( 'Footer Elementor', 'resido' ),
				'default' => false,
			),
			array(
				'id'       => $opt_prefix . 'footer_elementor_template',
				'required' => array( $opt_prefix . 'footer_elementor', '=', array( '1' ) ),
				'type'     => 'select',
				'multi'    => false,
				'title'    => esc_html__( 'Elementor Template', 'resido' ),
				'options'  => sds_elementor_library(),
			),
			array(
				'id'      => $opt_prefix . 'footer_copyright',
				'type'    => 'textarea',
				'title'   => esc_html__( 'Copyright', 'resido' ),
				'default' => esc_html__( '©2021 resido. All rights reserved.', 'resido' ),
			),
			array(
				'id'    => $opt_prefix . 'footer_social_info',
				'type'  => 'textarea',
				'title' => esc_html__( 'Social Info', 'resido' ),
			),
		),
	)
);
Redux::setSection(
	$opt_name,
	array(
		'title'            => esc_html__( 'Extra', 'resido' ),
		'id'               => 'resido_Extra',
		'customizer_width' => '400px',
		'icon'             => 'el el-list-alt',
		'fields'           => array(
			array(
				'id'      => $opt_prefix . 'theme_initialized',
				'type'    => 'switch',
				'title'   => esc_html__( 'Theme Initialized', 'resido' ),
				'default' => true,
			),
		),
	)
);
