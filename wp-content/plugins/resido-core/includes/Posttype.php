<?php
namespace Resido\Helper;

/**
 * The admin class
 */
class Posttype {

	/**
	 * Initialize the class
	 */
	function __construct() {
		if ( ! is_plugin_active( 'resido-listing/resido-listing.php' ) ) {
			new Posttype\Pricing();
			new Posttype\Agents();
			new Posttype\Agencies();
		}
	}
}
