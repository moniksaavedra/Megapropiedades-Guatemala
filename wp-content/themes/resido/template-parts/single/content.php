<?php
$blog_social_share = resido_get_options( 'blog_social_share' );
$blog_author_box   = resido_get_options( 'blog_author_box' );
$blog_post_nav     = resido_get_options( 'blog_post_nav' );
if ( is_active_sidebar( 'sidebar-1' ) ) :
	$blog_col_class = 'col-lg-8 col-sm-12';
else :
	$blog_col_class = 'col-lg-12 col-sm-12';
endif;
?>

<section class="gray-simple">
	<div class="container">
		<!-- row Start -->
		<div class="row">
			<!-- Blog Detail -->
			<div class="<?php echo esc_attr( $blog_col_class ); ?>">
				<div class="blog-details single-post-item format-standard">
					<div class="post-details">
						<?php resido_post_thumbnail(); ?>
						<div class="post-top-meta">
							<ul class="meta-comment-tag">
								<li><?php resido_posted_by(); ?></li>
								<li><?php resido_comments_count(); ?></li>
							</ul>
						</div>
						<h2 class="post-title"><?php the_title(); ?></h2>
						<div class="post-content">
							<?php
							the_content();
							wp_link_pages(
								array(
									'before' => '<div class="page-links">',
									'after'  => '</div>',
								)
							);
							?>
						</div>
						<?php
						if ( has_tag() != '' || $blog_social_share != 0 ) {
							?>
							<div class="post-bottom-meta">
								<?php
								resido_post_tags( get_the_ID() );
								if ( $blog_social_share != 0 ) { // markup switch
									?>
									<div class="post-share">
										<h4 class="pbm-title"><?php esc_html_e( 'Social Share', 'resido' ); ?></h4>
										<ul class="list">
											<?php resido_blog_social(); ?>
										</ul>
									</div>
								<?php } ?>
							</div>
							<?php
						}
						if ( $blog_post_nav != 0 ) {
							do_action( 'resido_navigation_post' );
						}
						?>
					</div>
				</div>
				<!-- Author Detail -->
				<?php
				if ( $blog_author_box == 1 ) { // markup switch
					do_action( 'resido_authore_box' );
				}
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				?>
			</div>
			<!-- Single blog Grid -->
			<?php if ( is_active_sidebar( 'sidebar-1' ) ) { ?>
				<div class="col-lg-4 col-md-12 col-sm-12 col-12">
					<?php get_sidebar(); ?>
				</div>
			<?php } ?>
		</div>
		<!-- /row -->
	</div>
</section>
