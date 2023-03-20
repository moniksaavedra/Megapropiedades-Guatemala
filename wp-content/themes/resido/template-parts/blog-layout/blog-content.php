<?php
$blog_layout = get_query_var('blog_layout');
if (!$blog_layout) {
	$blog_layout = resido_get_options('blog_layout');
}

if ($blog_layout == 2) :
	$layout_column = 'col-lg-4 col-md-6';
else :
	$layout_column = 'col-lg-12';
endif;

if (is_archive() || is_search()) {
	$layout_column = 'col-lg-12';
}

if (is_sticky()) {
	$is_sticky_class = 'sticky-post-class';
} else {
	$is_sticky_class = '';
}
?>
<!-- Single blog Grid -->
<div class="<?php echo esc_attr($layout_column . ' ' . $is_sticky_class); ?>">
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="blog-wrap-grid">
			<?php
			if (is_sticky()) {
				echo '<div class="sticky_post_icon " title="' . esc_attr__('Sticky Post', 'resido') . '"><i class="fas fa-map-pin"></i></div>';
			}
			?>
			<div class="blog-thumb">
				<?php
				if ($blog_layout == 2) :
					resido_post_grid_thumbnail();
				else :
					resido_post_thumbnail();
				endif;

				?>


			</div>

			<div class="blog-info">
				<span class="post-date"><i class="ti-calendar"></i><?php resido_posted_on(); ?></span>
				<span class="post-date"><?php resido_comments_count(); ?></span>
			</div>

			<div class="blog-body">
				<h4 class="bl-title"><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></h4>
				<?php
				if (!empty(get_the_excerpt())) :
					if (get_option('rss_use_excerpt')) {
						the_excerpt();
					} else {
						the_excerpt();
					}
				endif;
				wp_link_pages(
					array(
						'before' => '<div class="page-links">',
						'after'  => '</div>',
					)
				);
				?>
				<a href="<?php esc_url(the_permalink()); ?>" class="bl-continue"><?php esc_html_e('Continue', 'resido'); ?></a>
			</div>

		</div>
	</div>
</div>