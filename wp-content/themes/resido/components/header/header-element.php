<?php
$header_elementor_template = resido_get_options('header_elementor_template');
if (class_exists('\\Elementor\\Plugin')) {
	$pluginElementor = \Elementor\Plugin::instance();
	$resido_element = $pluginElementor->frontend->get_builder_content($header_elementor_template);
	echo do_shortcode($resido_element);
}
