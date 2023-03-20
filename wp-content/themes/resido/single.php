<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package resido
 */
get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/single/content', get_post_format() );
endwhile; // End of the loop.
get_footer();
