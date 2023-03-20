<?php

namespace bhr\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use bhr\Admin\Controller\CallbackController;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class RestApi {

	public function __construct() {
		add_action( 'rest_api_init', array( RestApi::class, 'register_endpoints' ) );
	}

	public static function register_endpoints() {
		try {
			// route url: domain.com/wp-json/$namespace/$route
			$namespace = 'salesmanago/v1';
			$route     = 'callbackApiV3';
			register_rest_route( $namespace, $route, array(
				'methods'   => WP_REST_Server::CREATABLE,
				'callback'  => array( 'bhr\Admin\RestApi', 'handle_callback_request' ),
				'args' => array(
					'sm_token' => array(
						'required' => true,
						'validate_callback' => function( $token ) {
							return self::validate_api_v3_callback( $token );
						}
					)
				)
			));
		} catch (Exception | Error $e) {
			Helper::salesmanago_log( $e->getMessage(), __FUNCTION__ );
		}
	}

	/**
	 * Handle callback request
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response response
	 */
	public static function handle_callback_request( WP_REST_Request $request )
	{
		$CallbackController = new CallbackController();
		return $CallbackController->log_callback_message( $request );
	}

	/**
	 * Validate the request using its token
	 * @param string $token
	 * @return bool is_valid_callback
	 */
	public static function validate_api_v3_callback( $token )
	{
		$CallbackController = new CallbackController();
		return $CallbackController->validate_api_v3_callback( $token );
	}
}
