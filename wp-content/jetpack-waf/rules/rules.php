<?php
if ( file_exists( 'C:\xampp\htdocs\Megapropiedades/wp-content/jetpack-waf/rules/allow-ip.php' ) ) { if ( require( 'C:\xampp\htdocs\Megapropiedades/wp-content/jetpack-waf/rules/allow-ip.php' ) ) { return; } }
if ( file_exists( 'C:\xampp\htdocs\Megapropiedades/wp-content/jetpack-waf/rules/block-ip.php' ) ) { if ( require( 'C:\xampp\htdocs\Megapropiedades/wp-content/jetpack-waf/rules/block-ip.php' ) ) { return $waf->block( 'block', -1, 'ip block list' ); } }
if ( file_exists( 'C:\xampp\htdocs\Megapropiedades/wp-content/jetpack-waf/rules/automatic-rules.php' ) ) { if ( require( 'C:\xampp\htdocs\Megapropiedades/wp-content/jetpack-waf/rules/automatic-rules.php' ) ) { return; } }
