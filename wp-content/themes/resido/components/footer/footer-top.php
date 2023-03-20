<?php
$footer_elementor = resido_get_options('footer_elementor');
$footer_elementor_template = resido_get_options('footer_elementor_template');
if (class_exists('\\Elementor\\Plugin')) {
	if (isset($footer_elementor) && !empty($footer_elementor)) :
		$pluginElementor               = \Elementor\Plugin::instance();
		$resido_all_ssave_element = $pluginElementor->frontend->get_builder_content($footer_elementor_template);
		echo do_shortcode($resido_all_ssave_element);
	endif;
}
