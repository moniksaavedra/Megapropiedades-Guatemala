<?php
namespace CVEC\classes\vc;

defined( 'ABSPATH' ) || die();

class pb_build_css {

	public static $targetdircss        = '/cache/css/';
	public static $targetdirurlcss     = '/cache/css/';
	public static $defaultcssname      = 'default.min.css';
	public static $defaultcsspath      = CVEC_DIR_PATH . 'assets/css/';
	public static $defaultcssURL       = CVEC_DIR_URL. 'assets/css/';
	public static $filename            = '';
	public static $filename_custom_css = '';
	public static function init() {
		$upload_dir            = wp_upload_dir();
		self::$targetdircss    = apply_filters( 'combine_vc_ele_css_pb_build_css_target_css_path', $upload_dir['basedir'] . self::$targetdircss );
		self::$defaultcssname  = apply_filters( 'combine_vc_ele_css_pb_build_css_assets_css_default_name', self::$defaultcssname );
		self::$defaultcsspath  = apply_filters( 'combine_vc_ele_css_pb_build_css_assets_css_path', self::$defaultcsspath );
		self::$defaultcssURL  = apply_filters( 'combine_vc_ele_css_pb_build_css_assets_css_url', self::$defaultcssURL );

		self::$targetdirurlcss = apply_filters( 'combine_vc_ele_css_pb_build_css_target_css_url', $upload_dir['baseurl'] . self::$targetdirurlcss );
	}

	public static function pb_build_css_assets_css( $post_id ) {

		self::$filename            = self::$targetdircss . "cvec_post_{$post_id}.css";
		self::$filename_custom_css = self::$targetdircss . "css_editor_{$post_id}.css";
		self::pb_build_css_remove_css();
		$array = get_post_meta( $post_id, CVEC_OPTION_NAME, true );
		$data  = '';
		if ( file_exists( self::$defaultcsspath . self::$defaultcssname ) ) {
			$data .= file_get_contents( self::$defaultcsspath . self::$defaultcssname );
		}
		if ( ! empty( $array ) ) {
			$array=array_column($array, 'css');
			$array = array_map("unserialize", array_unique(array_map("serialize", $array)));
			foreach ( $array as $sccss ) {
				foreach ( $sccss as $css ) {

					if ( file_exists( self::$defaultcsspath . "{$css}.min.css" ) ) {

						$data .= file_get_contents( self::$defaultcsspath . "{$css}.min.css" );
					}
				}
			}
			if ( ! is_dir( self::$targetdircss ) ) {
				@mkdir( self::$targetdircss, 0777, true );
			}

			if ( $data != '' ) {
				file_put_contents( self::$filename, $data );
			}
		}

	}

	public static function pb_get_css_assets_css() {
		global $post;
		if ( ! isset( $post ) || empty( $post ) ) {
			return;
		}
		// $array = get_post_meta($post->ID, CVEC_OPTION_NAME, true);
		self::$filename            = self::$targetdircss . "cvec_post_{$post->ID}.css";
		self::$filename_custom_css = self::$targetdircss . "css_editor_{$post->ID}.css";
		// if (!file_exists(self::$filename)) {
		// self::pb_build_css_assets_css($post->ID);
		// }
		$array = array();
		if ( file_exists( self::$filename ) ) {
			$path_parts                  = pathinfo( self::$filename );
			$array['return_url']         = self::$targetdirurlcss . $path_parts['basename'];
			$array['return_url_version'] = VERSION . '.' . get_post_modified_time( 'U', false, $post );
		}
		if ( file_exists( self::$filename_custom_css ) ) {
			$path_parts                         = pathinfo( self::$filename_custom_css );
			$array['return_url_custom']         = self::$targetdirurlcss . $path_parts['basename'];
			$array['return_url_custom_version'] = VERSION . '.' . get_post_modified_time( 'U', false, $post );
		}
		return $array;
	}


	public static function pb_get_css_assets_css_for_editor_mode($array) {
		global $post;
		if ( ! isset( $post ) || empty( $post ) ) {
			return;
		}
		$array=array_column($array, 'css');
		$array = array_map("unserialize", array_unique(array_map("serialize", $array)));
		foreach ( $array as $sccss ) {
			foreach ( $sccss as $css ) {

				if ( file_exists( self::$defaultcsspath . "{$css}.min.css" ) ) {
					wp_enqueue_style( 'combine-vc-ele-'.$css, self::$defaultcssURL . "{$css}.min.css", null, VERSION . '.' . get_post_modified_time( 'U', false, $post ) );
				}
			}
		}
	}


	public static function pb_build_css_remove_css( $post_id = '', $remove_custom = false ) {
		if ( $post_id != '' ) {
			self::$filename            = self::$targetdircss . "cvec_post_{$post_id}.css";
			self::$filename_custom_css = self::$targetdircss . "css_editor_{$post_id}.css";
		}

		if ( file_exists( self::$filename ) ) {
			unlink( self::$filename );
		}
		if ( $remove_custom ) {
			if ( file_exists( self::$filename_custom_css ) ) {
				unlink( self::$filename_custom_css );
			}
		}
	}

}
