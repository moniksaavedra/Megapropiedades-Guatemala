<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- Fav Icon -->
	<?php
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) { // since 4.3.0
		wp_site_icon();
	}
	wp_head();
	?>
</head>
<!-- page wrapper -->
<body <?php body_class(); ?>>
	<?php
	wp_body_open();
	$header_type = resido_get_options( 'header_type' );
	if ( $header_type ) {
		$header_type = resido_get_options( 'header_type' );
	} else {
		$header_type = '1';
	}

	$get_header_var = get_query_var( 'header_layout' ); // get query var value
	if ( isset( $get_header_var ) && $get_header_var != '' ) {
		$get_header_var       = explode( '_', $get_header_var );
		$header_default_style = $get_header_var[0];
	} else {
		$header_default_style = resido_get_options( 'header_default_style' );
		if ( $header_default_style ) {
			$header_default_style = resido_get_options( 'header_default_style' );
		} else {
			$header_default_style = '1';
		}
	}
	$resido_preloader = resido_get_options( 'preloader' );
	if ( $resido_preloader == '1' ) {
		do_action( 'resido_preloader' );
	}
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
	?>
	<div id="main-wrapper">
		<?php
		if ( $header_type == 1 ) {
			get_template_part( 'components/header/header-style-' . $header_default_style );
		} else {
			get_template_part( 'components/header/header-element' );
		}

		do_action( 'resido_breadcrumb' );
	}
