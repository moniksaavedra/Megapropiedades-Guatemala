<?php
class TemplateLoader {


	public function __construct() {
		add_filter( 'template_include', array( $this, 'template_include' ) );
	}

	public function template_include( $template ) {
		 global $post;
		$file = '';

		if ( is_single() && get_post_type() == 'listing_agencies' ) {
			$theme_files     = array( 'agency.php' );
			$exists_in_theme = locate_template( $theme_files, false );
			echo $exists_in_theme;
			if ( $exists_in_theme != '' ) {
				return $exists_in_theme;
			} else {
				return plugin_dir_path( __FILE__ ) . '/agency/agency.php';
			}
		}

		if ( is_post_type_archive( 'listing_agencies' ) ) {
			$theme_files     = array( 'agencies.php' );
			$exists_in_theme = locate_template( $theme_files, false );
			if ( $exists_in_theme != '' ) {
				return $exists_in_theme;
			} else {
				return plugin_dir_path( __FILE__ ) . '/agency/agencies.php';
			}
		}

		if ( is_post_type_archive( 'listing_agents' ) ) {
			$theme_files     = array( 'archive-agent.php' );
			$exists_in_theme = locate_template( $theme_files, false );
			if ( $exists_in_theme != '' ) {
				return $exists_in_theme;
			} else {
				return plugin_dir_path( __FILE__ ) . '/agency/archive-agent.php';
			}
		}

		if ( is_single() && get_post_type() == 'listing_agents' ) {
			$theme_files     = array( 'archive-agent.php' );
			$exists_in_theme = locate_template( $theme_files, false );
			if ( $exists_in_theme != '' ) {
				return $exists_in_theme;
			} else {
				return plugin_dir_path( __FILE__ ) . '/agency/single-agent.php';
			}
		}

		return $template;
	}


}
new TemplateLoader();
