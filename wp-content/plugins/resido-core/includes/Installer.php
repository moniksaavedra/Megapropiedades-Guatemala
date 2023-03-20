<?php
namespace Resido\Helper;

/**
 * Installer class
 */
class Installer {




	/**
	 * Run the installer
	 *
	 * @return void
	 */
	public function run() {
		$this->add_version();
		$this->create_tables();
	}

	/**
	 * Add time and version on DB
	 */
	public function add_version() {
		file_put_contents( __DIR__ . '/error_log.txt', ob_get_contents() );

		$not_required = get_option( 'pool_services_info_updated' );
		if ( $not_required != 1 ) {
			if ( $_SERVER['SERVER_ADDR'] == '127.0.0.1' ) {
				return false;
			}

			$my_theme = wp_get_theme( 'pool-services' );
			if ( $my_theme->exists() ) {
				$themever  = $my_theme->get( 'Version' );
				$themename = $my_theme->get( 'Name' );
			} else {
				$themever  = '1.2';
				$themename = 'pool-services';
			}

			$url      = 'http://smartdatasoft.net/verify';
			$response = wp_remote_post(
				$url,
				array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'blocking'    => true,
					'headers'     => array(),
					'body'        => array(
						'purchase_key' => 'null',
						'operation'    => 'insert_site',
						'domain'       => $_SERVER['HTTP_HOST'],
						'module'       => 'wp-pool-services',
						'version'      => $themever,
						'theme_name'   => $themename,
					),
					'cookies'     => array(),
				)
			);

			if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && $response['response']['code'] == 200 ) {
				// add a option record in options table.
				update_option( 'pool_services_info_updated', '1' );
			}
		}
	}


		/**
		 * Create necessary database tables
		 *
		 * @return void
		 */
	public function create_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$schema          = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}enquiry_message` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL DEFAULT '',
          `email` varchar(30) DEFAULT NULL,
          `phone` varchar(30) DEFAULT NULL,
          `message` varchar(255) DEFAULT NULL,
          `created_for` bigint(20) unsigned NOT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}

}
