<?php
namespace Resido\Helper\Elementor\Settings;

use Elementor\Utils;

class Header {






	public static function resido_get_wow_animation_control( $obj, $animationClass = 'fadeInLeft', $animationDuration = '1500ms', $animationDelay = '500ms' ) {
		$obj->add_control(
			'animation_class',
			array(
				'label'     => __( 'Animation', 'resido-core' ),
				'separator' => 'before',
				'type'      => \Elementor\Controls_Manager::ANIMATION,
				'default'   => $animationClass,
				'options'   => \Resido\Helper\Elementor\Settings\Animation::resido_get_animation_name(),
			)
		);

		$obj->add_control(
			'duration_time',
			array(
				'label'       => __( 'Duration Time', 'resido-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $animationDuration,
				'placeholder' => esc_html__( 'Animation duration value ex:200s.', 'resido-core' ),
			)
		);

		$obj->add_control(
			'delay_time',
			array(
				'label'       => __( 'Delay Time', 'resido-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $animationDelay,
				'placeholder' => esc_html__( 'Animation duration value ex:200s.', 'resido-core' ),
			)
		);
	}

	public static function resido_coloumn( $obj, $settings ) {
		$number_of_coloumns = $settings['number_of_coloumns'];
		return $number_of_coloumns;
	}


	public static function resido_coloumn_control( $obj, $max = 3, $min = 1 ) {
		$option = array();

		for ( $i = $min; $i <= $max; $i++ ) {
			switch ( $i ) {
				case 1:
					$option['col-lg-12'] = __( '1', 'resido-core' );
					break;
				case 2:
					$option['col-lg-6'] = __( '2', 'resido-core' );
					break;
				case 3:
					$option['col-lg-4'] = __( '3', 'resido-core' );
					break;
				case 4:
					$option['col-lg-3'] = __( '4', 'resido-core' );
					break;
				case 6:
					$option['col-lg-2'] = __( '6', 'resido-core' );
					break;
				case 12:
					$option['col-lg-1'] = __( '12', 'resido-core' );
					break;
			}
		}

		$obj->add_control(
			'number_of_coloumns',
			array(
				'label'     => __( 'Number Of Coloumns', 'resido-core' ),
				'separator' => 'before',
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => $option,
				'default'   => 'col-lg-4',

			)
		);
	}

	public static function getContactForm7Posts() {
		 $args    = array(
			 'post_type'      => 'wpcf7_contact_form',
			 'posts_per_page' => -1,
		 );
		 $catlist = array();
		 if ( $categories = get_posts( $args ) ) {
			 foreach ( $categories as $category ) {
				 (int) $catlist[ $category->ID ] = $category->post_title;
			 }
		 } else {
			 (int) $catlist['0'] = esc_html__( 'No contect From 7 form found', 'resido-core' );
		 }
		 return $catlist;
	}
}
