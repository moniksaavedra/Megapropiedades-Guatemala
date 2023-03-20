<?php
get_header();
$paged    = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$wp_query = new WP_Query(
	array(
		'post_type'   => 'listing_agents',
		'post_status' => 'publish',
		'paged'       => $paged,
		'orderby'     => 'title',
		'order'       => 'ASC',
	)
);
$prefix   = 'resido_agents_';
?>
<!-- ============================ Agent List Start ================================== -->
<section class="gray-simple">
	<div class="container">
		<div class="row">
			<?php
			if ( $wp_query->have_posts() ) {
				while ( $wp_query->have_posts() ) {
					$wp_query->the_post();
					?>
					<!-- Single Agent -->
					<div class="col-lg-4 col-md-6 col-sm-12">
						<div class="agents-grid">
							<div class="agents-grid-wrap">
								<div class="fr-grid-thumb">
									<a href="<?php the_permalink(); ?>">
										<?php if ( has_post_thumbnail() ) { ?>
											<img src="<?php echo get_the_post_thumbnail_url( get_the_ID() ); ?>" class="img-fluid mx-auto" alt="" />
										<?php } else { ?>
											<img src="<?php echo plugins_url( 'resido-core' ) . '/assets/img/placeholder.png'; ?>" alt="">
										<?php } ?>
									</a>
								</div>
								<div class="fr-grid-deatil">
									<div class="fr-grid-deatil-flex">
										<h5 class="fr-can-name"><a href="<?php esc_url( the_permalink() ); ?>"><?php the_title(); ?></a></h5>
										<?php
										$agent_args        = array(
											'post_type'   => 'cl_cpt',
											'post_status' => array( 'publish' ),
											'posts_per_page' => -1, // no limit,
											'meta_query'  => array(
												array(
													'key' => 'resido_listing_rlagentinfo',
													'value' => get_the_ID(),
													'compare' => '=',
												),
											),
										);
										$agent_count_query = new WP_Query( $agent_args );
										if ( $agent_count_query->post_count > 1 ) {
											$property_text = esc_html__( 'Properties', 'resido-core' );
										} else {
											$property_text = esc_html__( 'Property', 'resido-core' );
										}
										?>
										<span class="agent-property"><?php echo esc_html( $agent_count_query->post_count . ' ' . $property_text ); ?></span>
									</div>
									<?php
									if ( get_post_meta( get_the_ID(), $prefix . 'agent_email', true ) != '' ) {
										?>
									<div class="fr-grid-deatil-flex-right">
										<div class="agent-email"><a href="mailto:<?php listing_meta_field( $prefix . 'agent_email' ); ?>"><i class="ti-email"></i></a></div>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="fr-grid-info">
								<ul>
									<?php
									if ( get_post_meta( get_the_ID(), $prefix . 'agent_cell', true ) != '' ) {
										?>
										<li><strong><?php echo esc_html__( 'Call:', 'resido-core' ); ?></strong> <?php listing_meta_field( $prefix . 'agent_cell' ); ?></li>
									<?php } ?>
								</ul>
							</div>
							<div class="fr-grid-footer">
								<?php
								if ( get_post_meta( get_the_ID(), $prefix . 'agent_address', true ) != '' ) {
									?>
									<div class="fr-grid-footer-flex">
										<span class="fr-position"><i class="lni-map-marker"></i><?php listing_meta_field( $prefix . 'agent_address' ); ?></span>
									</div>
								<?php } ?>
								<div class="fr-grid-footer-flex-right">
									<a href="<?php esc_url( the_permalink() ); ?>" class="prt-view" tabindex="0"><?php esc_html_e( 'View', 'resido-core' ); ?></a>
								</div>
							</div>
						</div>
					</div>
					<!-- Single Agent -->
					<?php
				}
			}
			wp_reset_query();
			?>
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
			<!-- Pagination -->
		</div>
	</div>
</section>
<?php
get_footer();
