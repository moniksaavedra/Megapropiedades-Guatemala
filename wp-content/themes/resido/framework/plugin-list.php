<?php
trait pluginlist {

	public $plugin_list           = array(
		'resido-core',
		'resido-demo-installer',
		'woo-payments-for-essential-real-estate',
	);
	
	public $dashboard_Name        = 'Resido';
	public $dashboard_slug        = 'resido-activation';
	public $menu_slug_dashboard   = 'envato-theme-license-dashboard';
	public $menu_slug             = 'envato-theme-license-';
	public $plugin_list_with_file = array(
		'resido-core'           => 'resido-core.php',
		'resido-demo-installer' => 'resido-demo-installer.php',
		'woo-payments-for-essential-real-estate' => 'index.php',
	);
	public $plugin_org_name       = array(
		'resido-core'           => 'Resido Core',
		'resido-demo-installer' => 'Resido Demo Installer',
		'woo-payments-for-essential-real-estate' => 'Woo Payments for Essential Real Estate',
	);
	public $doc_url               = 'https://smartdatasoft.com/docs/resido-real-estate-listing-wordpress-theme/';
	public $update_url            = 'https://my.smartdatasoft.com/';
	public $themeitem_id          = '31804443';
}
