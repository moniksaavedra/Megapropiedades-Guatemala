<?php
define( 'DISABLE_JETPACK_WAF', false );
if ( defined( 'DISABLE_JETPACK_WAF' ) && DISABLE_JETPACK_WAF ) return;
define( 'JETPACK_WAF_MODE', 'normal' );
define( 'JETPACK_WAF_SHARE_DATA', '1' );
define( 'JETPACK_WAF_DIR', 'C:\\xampp\\htdocs\\Megapropiedades/wp-content/jetpack-waf' );
define( 'JETPACK_WAF_WPCONFIG', 'C:\\xampp\\htdocs\\Megapropiedades/wp-content/../wp-config.php' );
require_once 'C:\\xampp\\htdocs\\Megapropiedades\\wp-content\\plugins\\jetpack/vendor/autoload.php';
Automattic\Jetpack\Waf\Waf_Runner::initialize();