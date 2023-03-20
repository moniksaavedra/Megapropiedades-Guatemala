<?php
namespace Resido\Helper\Elementor;

class Icon {



	public function __construct() {
		 add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'custom_icon' ) );
	}

	function custom_icon( $array ) {
		$plugin_url = plugins_url();

		return array(
			'custom-icon' => array(
				'name'          => 'custom-icon',
				'label'         => 'Resido Icon',
				'url'           => '',
				'enqueue'       => array(
					$plugin_url . '/resido-core/assets/elementor/icon/themify-icon.css',
				),
				'prefix'        => '',
				'displayPrefix' => '',
				'labelIcon'     => 'resido-icon',
				'ver'           => '',
				'fetchJson'     => $plugin_url . '/resido-core/assets/elementor/icon/js/regular.json',
				'native'        => 1,
			),
		);
	}
}
