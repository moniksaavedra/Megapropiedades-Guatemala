<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package resido
 */
get_header();
if ( comments_open() ) {
	$comment_enabled_class = 'comment_enabled_page';
} else {
	$comment_enabled_class = '';
}

$resido_core_page_no_pad = get_post_meta( get_the_id(), 'resido_core_page_no_pad', true );
$sidebar_selected        = get_post_meta( get_the_ID(), 'resido_core_sidebar_list', true );
$page_sidebar_option     = isset( $page_sidebar_option ) ? $page_sidebar_option : '2';

if ( isset( $resido_core_page_no_pad ) && $resido_core_page_no_pad == 1 ) {
	$fl_wth_container_cls = '';
	$fl_wth_sec_cls       = 'full_width_sac';
} else {
	$fl_wth_container_cls = 'container';
	$fl_wth_sec_cls       = null;
}

if ( $sidebar_selected ) {
	$sidebar_cont_class = 'sidebar-page-container';
} else {
	$sidebar_cont_class = 'no-sidebar-page';
}

if ( ( $page_sidebar_option == '1' ) || ( $page_sidebar_option == '2' ) ) {
	if ( $sidebar_selected ) {
		$resido_container_class = 'col-lg-8 col-sm-12';
	} else {
		$resido_container_class = 'col-lg-12';
	}
} else {
	$resido_container_class = 'col-lg-12';
}
$page_sidebar = get_post_meta( get_the_ID(), 'framework_page_sidebar', true );
?>

<!--Sidebar Page Container-->
<section class="page_layout <?php echo esc_attr( $comment_enabled_class . ' ' . $fl_wth_sec_cls . ' ' . $sidebar_cont_class ); ?>">
	<div class="<?php echo esc_attr( $fl_wth_container_cls ); ?>">
		<div class="row">
			<?php
			do_action( 'sidebar_left' );
			?>
			<!--Content Side / Blog Sidebar-->
			<div class="<?php echo esc_attr( $resido_container_class ); ?>">
				<div class="blog-content blog-details-content">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<div class="page-content">
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
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile; // End of the loop.
					?>
				</div>
			</div>
			<?php
			do_action( 'sidebar_right' );
			?>
		</div>
	</div>
</section>
<?php
get_footer();
