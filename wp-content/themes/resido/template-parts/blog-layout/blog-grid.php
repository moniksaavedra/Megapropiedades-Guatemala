<!-- ============================ Agency Grid Start ================================== -->
<?php
$blog_title    = resido_get_options( 'blog_title' );
$blog_subtitle = resido_get_options( 'blog_subtitle' );
?>
<section class="gray-simple">
	<div class="container">
		<?php if ( $blog_title || $blog_subtitle ) { ?>
			<div class="row">
				<div class="col text-center">
					<div class="sec-heading center">
						<?php if ( $blog_title ) { ?>
							<h2><?php echo esc_html( $blog_title ); ?></h2>
							<?php
						}
						if ( $blog_subtitle ) {
							?>
							<p><?php echo esc_html( $blog_subtitle ); ?></p>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>

		<!-- row Start -->
		<div class="row">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/blog-layout/blog-content' );
				endwhile;
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif;
			?>

		</div>
		<!-- /row -->

		<!-- Pagination -->
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<ul class="pagination p-center">
					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 2,
							'prev_text' => '<span class="ti-arrow-left"></span>',
							'next_text' => '<span class="ti-arrow-right"></span>',
						)
					);
					?>
				</ul>
			</div>
		</div>
	</div>
</section>
