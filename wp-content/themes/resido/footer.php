<?php
$back_to_top        = resido_get_options( 'back_to_top' );
$footer_social_info = resido_get_options( 'footer_social_info' );
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
?>
<!-- ============================ Footer Start ================================== -->
<footer class="dark-footer skin-dark-footer">
	<div>
		<?php get_template_part( 'components/footer/footer-top' ); ?>
	</div>

	<div class="footer-bottom">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6 col-md-6">
					<p class="mb-0">
						<?php
						$footer_copyright = resido_get_options( 'footer_copyright' );
						if ( $footer_copyright != '' ) :
							echo wp_kses( $footer_copyright, 'code_contxt' );
						else :
							echo esc_html__( '©2021 resido. All rights reserved.', 'resido' );
						endif;
						?>
					</p>
				</div>
				<?php
				if ( $footer_social_info ) {
					?>
					<div class="col-lg-6 col-md-6 text-right">
						<ul class="footer-bottom-social">
							<?php echo wp_kses( $footer_social_info, 'code_contxt' ); ?>
						</ul>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</footer>
<!-- ============================ Footer End ================================== -->

<?php
}
if ( $back_to_top == 1 ) {
	?>
	<a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>
<?php } ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
