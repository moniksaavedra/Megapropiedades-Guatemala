<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package resido
 */

get_header();

$blog_layout = get_query_var( 'blog_layout' );
if ( ! $blog_layout ) {
	$blog_layout = resido_get_options( 'blog_layout' );
}
if ( $blog_layout == 2 ) :
	$blog_layout_name = 'grid';
else :
	$blog_layout_name = 'standard';
endif;

get_template_part( 'template-parts/blog-layout/blog-' . $blog_layout_name );

get_footer();
