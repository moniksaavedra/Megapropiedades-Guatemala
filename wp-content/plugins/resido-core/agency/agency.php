<?php
get_header();

$author_id      = $post->post_author;
$author_name    = get_the_author_meta( 'display_name', $author_id );
$current_user   = wp_get_current_user();
$user_id        = $current_user->ID;
$usr_img_url    = get_avatar_url( $user_id );
$rcity          = '';
$rstate         = '';
$rcity          = get_user_meta( $user_id, 'rcity', true );
$rstate         = get_user_meta( $user_id, 'rstate', true );
$query_variable = get_query_var( 'dashboard' );
?>
<!-- ============================ Page Title Start================================== -->
<div class="image-cover page-title">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<h1 class="ipt-title"><?php echo esc_html__( 'Agency Details', 'resido-core' ); ?></h1>
				<span class="ipn-subtitle">
					<?php
					the_title();
					echo esc_html( ' From ' );
					listing_meta_field( 'resido_agencies_agency_address' );
					?>
				</span>
			</div>
		</div>
	</div>
</div>
<!-- ============================ Agency Name Start================================== -->
<section class="agent-page p-0 gray-simple">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="agency agency-list overlio-40">
					<div class="agency-avatar">
						<img src="<?php echo get_the_post_thumbnail_url( get_the_ID() ); ?>" alt="">
					</div>
					<div class="agency-content">
						<div class="agency-name">
							<h4><?php the_title(); ?></h4>
							<?php
							if ( get_post_meta( get_the_ID(), 'resido_agencies_agency_address', true ) != '' ) {
								?>
								<span><i class="lni-map-marker"></i><?php listing_meta_field( 'resido_agencies_agency_address' ); ?></span>
							<?php } ?>
						</div>
						<div class="agency-desc">
							<?php echo the_content(); ?>
						</div>
						<div class="prt-detio">
							<?php
							$agent_args        = array(
								'post_type'      => 'cl_cpt',
								'post_status'    => array( 'publish' ),
								'posts_per_page' => -1, // no limit,
								'meta_query'     => array(
									array(
										'key'     => 'resido_listing_rlagencyinfo',
										'value'   => get_the_ID(),
										'compare' => '=',
									),
								),
							);
							$agent_count_query = new WP_Query( $agent_args );
							if ( $agent_count_query->post_count > 1 ) {
								$property_text = __( 'Properties', 'resido-core' );
							} else {
								$property_text = __( 'Property', 'resido-core' );
							}
							?>
							<span><?php echo esc_html( $agent_count_query->post_count . ' ' . $property_text ); ?></span>
						</div>
						<?php
						echo get_post_meta( get_the_ID(), 'resido_agencies_agency_social', true );
						?>
						<div class="clearfix"></div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>
<!-- ============================ Property Detail Start ================================== -->
<section class="gray-simple">
	<div class="container">
		<div class="row">
			<!-- property main detail -->
			<div class="col-lg-8 col-md-12 col-sm-12 bfgcvbh">
				<div class="block-wrap">
					<div class="block-header ">
						<h4 class="block-title"><?php echo esc_html__( 'Agency Info', 'resido-core' ); ?></h4>
					</div>
					<div class="block-body">
						<?php echo get_post_meta( get_the_ID(), 'resido_agencies_agency_information', true ); ?>
					</div>
				</div>
				<div class="block-wraps">
					<div class="block-wraps-header">
						<div class="block-header">
							<ul class="nav nav-tabs customize-tab" id="myTab" role="tablist">
								<li class="nav-item" role="presentation">
									<a class="nav-link active" id="agent-tab" data-bs-toggle="tab" href="#agent" role="tab" aria-controls="agent" aria-selected="true"><?php echo esc_html__( 'Agents', 'resido-core' ); ?></a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" id="property-tab" data-bs-toggle="tab" href="#property" role="tab" aria-controls="property" aria-selected="false"><?php echo esc_html__( 'Property', 'resido-core' ); ?></a>
								</li>
							</ul>
						</div>
						<div class="block-body">
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane fade show active" id="agent" role="tabpanel" aria-labelledby="agent-tab">
									<div class="row">
										<?php
										$args                = array(
											'post_type'   => 'listing_agents',
											'post_status' => array( 'publish' ),
											'posts_per_page' => -1, // no limit,
											'meta_query'  => array(
												array(
													'key' => 'resido_agents_parent_agency',
													'value' => get_the_ID(),
													'compare' => '=',
												),
											),
										);
										$current_agent_posts = get_posts( $args );
										if ( ! empty( $current_agent_posts ) ) {
											foreach ( $current_agent_posts as $single_post ) {
												$address  = get_post_meta( $single_post->ID, 'wperesds_address', true );
												$comments = get_comments( array( 'post_id' => $single_post->ID ) );
												$price    = get_post_meta( $single_post->ID, 'wperesds_pricing', true );
												?>
												<!-- Single Agent -->
												<div class="col-lg-6 col-md-6 col-sm-12">
													<div class="agents-grid">
														<div class="agents-grid-wrap">
															<div class="fr-grid-thumb">
																<?php if ( has_post_thumbnail( $single_post->ID ) ) { ?>
																	<a href="<?php echo get_permalink( $single_post->ID ); ?>">
																		<?php echo get_the_post_thumbnail( $single_post->ID, array( 240, 240 ) ); ?>
																	</a>
																<?php } else { ?>
																	<img src="<?php echo RESIDO_IMG_URL . 'placeholder.png'; ?>" alt="">
																<?php } ?>
															</div>
															<div class="fr-grid-deatil">
																<div class="fr-grid-deatil-flex">
																	<h5 class="fr-can-name"><a href="<?php echo get_permalink( $single_post->ID ); ?>"><?php echo $single_post->post_title; ?></a>
																	</h5>
																	<?php
																	$agent_args        = array(
																		'post_type' => 'cl_cpt',
																		'post_status' => array( 'publish' ),
																		'posts_per_page' => -1, // no limit,
																		'meta_query' => array(
																			array(
																				'key' => 'resido_listing_rlagentinfo',
																				'value' => $single_post->ID,
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
																<div class="fr-grid-deatil-flex-right">
																	<div class="agent-email"><a href="mailto:<?php echo get_post_meta( $single_post->ID, 'resido_agents_agent_email', true ); ?>"><i class="ti-email"></i></a></div>
																</div>
															</div>
														</div>
														<div class="fr-grid-info">
															<ul>
																<li><strong><?php echo esc_html__( 'Call:', 'resido-core' ); ?></strong><?php echo get_post_meta( $single_post->ID, 'resido_agents_agent_cell', true ); ?>
																</li>
															</ul>
														</div>
														<div class="fr-grid-footer">
															<div class="fr-grid-footer-flex">
																<span class="fr-position"><i class="lni-map-marker"></i><?php echo get_post_meta( $single_post->ID, 'resido_agents_agent_address', true ); ?></span>
															</div>
															<div class="fr-grid-footer-flex-right">
																<a href="<?php echo get_permalink( $single_post->ID ); ?>" class="prt-view" tabindex="0"><?php esc_html_e( 'View', 'resido-core' ); ?></a>
															</div>
														</div>
													</div>
												</div>
												<!-- Single Agent -->
												<?php
											}
										}
										?>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 text-center">
											<a href="<?php echo get_post_type_archive_link( 'listing_agents' ); ?>" class="btn btn-theme-light-2 rounded"><?php echo esc_html__( 'Explore More Agents', 'resido-core' ); ?></a>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="property" role="tabpanel" aria-labelledby="property-tab">
									<!-- row -->
									<div class="row">
										<?php
										$args     = array(
											'post_type'   => 'cl_cpt',
											'post_status' => array( 'publish' ),
											'posts_per_page' => -1, // no limit,
											'meta_query'  => array(
												array(
													'key' => 'resido_listing_rlagencyinfo',
													'value' => get_the_ID(),
													'compare' => '=',
												),
											),
										);
										$wp_query = new WP_Query( $args );
										if ( $wp_query->have_posts() ) {
											while ( $wp_query->have_posts() ) {
												$wp_query->the_post();
												$term_name       = wp_get_object_terms( get_the_ID(), 'listings_property', array( 'fields' => 'names' ) );
												$term_ID         = wp_get_object_terms( get_the_ID(), 'listings_property' );
												$rlisting_status = wp_get_object_terms( get_the_ID(), 'listing_status', array( 'fields' => 'names' ) );
												$column          = 'col-lg-6 col-md-6';
												?>
												<div class="col-lg-6 col-md-6">
													<div class="property-listing property-2">
														<div class="listing-img-wrapper">
															<div class="list-img-slide">
																<div class="click">
																	<?php if ( has_post_thumbnail( get_the_ID() ) ) { ?>
																		<div>
																			<a href="<?php esc_url( the_permalink() ); ?>"><?php the_post_thumbnail(); ?></a>
																		</div>
																		<?php
																	}
																	$galleryImage = get_post_meta( get_the_ID(), 'wperesds_gallery', true );
																	if ( ! empty( $galleryImage ) ) {
																		foreach ( $galleryImage as $image_id ) {
																			$image_url = wp_get_attachment_url( $image_id );
																			?>
																			<div>
																				<a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url( $image_url ); ?>" class="img-fluid mx-auto" alt="" /></a>
																			</div>
																			<?php
																		}
																	}
																	?>
																</div>
															</div>
														</div>
														<div class="listing-detail-wrapper">
															<div class="listing-short-detail-wrap">
																	<!-- Grid Layout -->
																	<div class="listing-short-detail">
																		<span class="property-type"><?php echo $rlisting_status[0]; ?></span>
																		<h4 class="listing-name verified">
																			<a href="<?php esc_url( the_permalink() ); ?>" class="prt-link-detail"><?php the_title(); ?></a>
																		</h4>
																	</div>
																	<!-- Grid Layout -->
																<div class="listing-short-detail-flex">
																	<h6 class="listing-card-info-price">
																		<?php
																		echo resido_currency_symbol();
																		listing_meta_field( 'wperesds_pricing' );
																		?>
																	</h6>
																</div>
															</div>
														</div>
														<div class="price-features-wrapper">
															<div class="list-fx-features">
																<div class="listing-card-info-icon">
																	<div class="inc-fleat-icon">
																		<img src="<?php echo RESIDO_IMG_URL . 'bed.svg'; ?>" width="13" alt="" />
																	</div>
																	<?php
																	listing_meta_field( 'wperesds_beds' );
																	echo esc_html__( ' Beds', 'resido-core' );
																	?>
																</div>
																<div class="listing-card-info-icon">
																	<div class="inc-fleat-icon">
																		<img src="<?php echo RESIDO_IMG_URL . 'bathtub.svg'; ?>" width="13" alt="" />
																	</div>
																	<?php
																	listing_meta_field( 'wperesds_bath' );
																	if ( get_post_meta( get_the_ID(), 'wperesds_bath', true ) == 1 ) {
																		echo esc_html__( ' Bath', 'resido-core' );
																	} else {
																		echo esc_html__( ' Baths', 'resido-core' );
																	}
																	?>
																</div>
																<div class="listing-card-info-icon">
																	<div class="inc-fleat-icon">
																		<img src="<?php echo RESIDO_IMG_URL . 'move.svg'; ?>" width="13" alt="" />
																	</div>
																	<?php listing_meta_field( 'wperesds_area' ) . ' ' . esc_html_e( 'sqft', 'resido-core' ); ?>
																</div>
															</div>
														</div>
														<div class="listing-detail-footer">
															<div class="footer-first">
																<?php if ( get_post_meta( get_the_ID(), 'wperesds_address', true ) ) { ?>
																	<div class="foot-location">
																		<img src="<?php echo RESIDO_IMG_URL . 'pin.svg'; ?>" width="18" alt="" /><?php listing_meta_field( 'wperesds_address' ); ?>
																	</div>
																<?php } ?>
															</div>
															<div class="footer-flex">
																<a href="<?php esc_url( the_permalink() ); ?>" class="prt-view"><?php esc_html_e( 'View', 'resido-core' ); ?></a>
															</div>
														</div>
													</div>
												</div>
												<!-- End Single Property -->
												<?php
											}
										};
										?>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 text-center">
											<a href=" <?php echo get_post_type_archive_link( 'cl_cpt' ); ?>" class="btn btn-theme-light-2 rounded"><?php echo esc_html_e( 'Explore More Property', 'resido-core' ); ?></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- property Sidebar -->
			<div class="col-lg-4 col-md-12 col-sm-12">
				<!-- Like And Share -->
				<div class="details-sidebar">
					<?php if ( is_active_sidebar( 'listing-single' ) ) { ?>
						<?php dynamic_sidebar( 'listing-single' ); ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
