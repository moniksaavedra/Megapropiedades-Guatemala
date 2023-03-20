<?php
namespace Resido\Helper;

/**
 * The admin class
 */
class Elementor {

	/**
	 * Initialize the class
	 */
	function __construct() {
		new Elementor\Element();
		new Elementor\Icon();
		new Elementor\Scripts();
	}
}
