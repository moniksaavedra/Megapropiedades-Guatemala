<?php
/**
 * Plugin Name: SALESmanago
 * Plugin URI:  https://www.salesmanago.com/?utm_source=integration&utm_medium=WORDPRESS&utm_content=marketplace
 * Description: SALESmanago Marketing Automation integration for Wordpress, WooCommerce, Contact Form 7, Gravity Forms
 * Version:     3.2.0
 * Tested up to: 5.7
 * Requires PHP: 7.1
 * Author:      SALESmanago
 * Author URI:  https://www.salesmanago.com/?utm_source=integration&utm_medium=WORDPRESS&utm_content=marketplace
 * License:     License: GPL2
 */

//avoid direct calls to this file, because now WP core and framework has been used
if (!function_exists('add_filter')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!defined('ABSPATH')) exit;

const SUPPORTED_PLUGINS = array(
    'WordPress' => 'wp',
    'WooCommerce' => 'wc',
    'Gravity Forms' => 'gf',
    'Contact Form 7' => 'cf7',
    'Fluent Forms' => 'ff'
);

define('SM_VERSION', json_decode(file_get_contents(dirname(__FILE__) . '/composer.json'), true)['version']);

const
    SALESMANAGO = 'salesmanago',
    SALESMANAGO_OWNER = 'salesmanago_refresh';

require_once __DIR__.'/vendor/autoload.php';

use bhr\Admin\Admin;
use bhr\Admin\Controller\ExportController;
use bhr\Admin\RestApi;
use bhr\Frontend\Frontend;

/* Note: Fluent Forms requests use admin context however we want to use frontend context to process the submitted forms */
if(is_admin() && isset($_REQUEST['action']) && strpos($_REQUEST['action'], SALESMANAGO . '_export') !== false) {
    new ExportController();
} elseif(is_admin() && (empty($_REQUEST['action']) || $_REQUEST['action'] !== 'fluentform_submit')) {
    new Admin();
} elseif(!is_admin() || (!empty($_REQUEST['action']) && $_REQUEST['action'] === 'fluentform_submit')) {
    new Frontend();
}

try {
	new RestApi();
} catch ( Exception | Error $e ) {
	error_log( $e->getMessage() );
}

