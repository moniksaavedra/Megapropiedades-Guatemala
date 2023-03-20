<?php
namespace Resido\Helper\Elementor;

use Elementor\Plugin;

class Element {

	public function __construct() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_registered' ) );
	}
	public function widgets_registered() {

			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Smart_Testimonials() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Step_How_To_Use() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Download_App() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Call_To_Action() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Achievement() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Blog_Post() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Contact_Form() );
		if ( ! is_plugin_active( 'resido-listing/resido-listing.php' ) ) {
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets2\Home_Banner() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets2\Property_Location() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets2\Explore_Property() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets2\Price_Table() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets2\Advance_Search() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets2\Explore_Agents() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets2\Widget_Map() );
		} else {
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Home_Banner() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Property_Location() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Explore_Property() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Price_Table() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Advance_Search() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Explore_Agents() );
			Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Widget_Map() );
		}

	}

	function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'resido',
			array(
				'title' => __( 'Resido', 'resido-core' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

}
