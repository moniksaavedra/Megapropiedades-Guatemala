<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package resido
 */
?>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'resido' ); ?></p>
			<div class="single-widgets widget_search">
				<div class="widget-inner">
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
