<?php
namespace Resido\Helper;

/**
 * The admin class
 */
class Admin {

	/**
	 * Initialize the class
	 */
	function __construct() {
		new Admin\Metabox\Metabox();
	}
}
