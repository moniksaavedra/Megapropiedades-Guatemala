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
?>
<section class="error-wrap">
	<div class="container">
		<div class="row justify-content-center">

			<div class="col-lg-6 col-md-10">
				<div class="text-center">
					<div class="error-title">
						<h2><?php esc_html_e( '404', 'resido' ); ?></h2>
						<h4><?php esc_html_e( 'Page Not Found', 'resido' ); ?></h4>
					</div>
					<p><?php esc_html_e( 'Maecenas quis consequat libero, a feugiat eros. Nunc ut lacinia tortor morbi ultricies laoreet ullamcorper phasellus semper', 'resido' ); ?></p>
					<a class="btn btn-theme" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back To Home', 'resido' ); ?></a>
				</div>
			</div>

		</div>
	</div>
</section>
<?php
get_footer();
