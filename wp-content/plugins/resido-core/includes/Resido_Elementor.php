<?php
namespace Resido\Helper;

/**
 * The admin class
 */
class Resido_Elementor {

	/**
	 * Initialize the class
	 */
	function __construct() {
		new Elementor\Element();
		new Elementor\Icon();
		new Elementor\Scripts();
	}
}
