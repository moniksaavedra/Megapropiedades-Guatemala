<?php
$current_user = wp_get_current_user();
$editpostid   = get_query_var( 'editagent' );

if ( isset( $_POST['agentsubmit'] ) && $_POST['agentsubmit'] ) {
	$post_arr = array(
		'post_title'   => $_POST['agent_title'],
		'post_content' => $_POST['agent_content'],
		'post_type'    => 'listing_agents',
		'post_status'  => 'publish',
		'post_author'  => get_current_user_id(),
		'meta_input'   => array(
			'resido_agents_agent_address'     => $_POST['agent_address'],
			'resido_agents_agent_cell'        => $_POST['agent_cell'],
			'resido_agents_agent_email'       => $_POST['agent_email'],
			'resido_agents_agent_social'      => $_POST['agent_social'],
			'resido_agents_agent_information' => $_POST['agent_info'],
			'resido_agents_parent_agent'      => $editpostid,
		),
	);
	$post_id  = wp_insert_post( $post_arr );
	set_post_thumbnail( $post_id, $_POST['frontend_rlfeaturedimg'] );
}
?>
<div class="agent-block dashboard-wraper form-submit">
	<div class="row">
		<h2><?php esc_html_e( 'Agent list', 'resido' ); ?></h2>
		<?php
		$args = array(
			'author'         => $current_user->ID,
			'post_type'      => 'listing_agents',
			'post_status'    => array( 'publish', 'pending' ),
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'posts_per_page' => -1, // no limit,
		);

		$current_user_posts = get_posts( $args );
		if ( ! empty( $current_user_posts ) ) {
			foreach ( $current_user_posts as $single_post ) {
				$address  = get_post_meta( $single_post->ID, 'resido_agents_agent_address', true );
				$comments = get_comments( array( 'post_id' => $single_post->ID ) );
				?>
				<!-- Agent -->
				<div class="col-lg-3 col-md-4 col-sm-12">
					<div class="agents-grid">
						<div class="agents-grid-wrap">
							<div class="fr-grid-thumb">
								<?php if ( has_post_thumbnail( $single_post->ID ) ) { ?>
									<a href="<?php echo get_permalink( $single_post->ID ); ?>">
										<?php echo get_the_post_thumbnail( $single_post->ID, array( 240, 240 ) ); ?>
									</a>
								<?php } else { ?>
									<img src="<?php echo plugins_url( 'resido' ) . '/assets/img/placeholder.png'; ?>" alt="<?php esc_attr_e( 'Placeholder', 'resido' ); ?>">
								<?php } ?>
							</div>
							<div class="fr-grid-deatil">
								<div class="fr-grid-deatil-flex">
									<h5 class="fr-can-name"><a href="<?php echo site_url( 'dashboard/?dashboard=agent&editagent=' . $single_post->ID ); ?>"><?php echo esc_html( $single_post->post_title ); ?></a></h5>
								</div>
								<div class="fr-grid-deatil-flex-right">
									<div class="agent-email">
										<a href="<?php echo site_url( 'dashboard/?dashboard=agent&editagent=' . $single_post->ID ); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="ti-pencil"></i></a>
										<?php
										if ( current_user_can( 'administrator' ) ) {
											?>
											<a onclick="return confirm('Do you really want to delete this Listing?')" href="<?php echo get_delete_post_link( $single_post->ID ); ?>" data-toggle="tooltip" data-placement="top" title="Delete Property" class="delete"><i class="ti-close"></i></a>
											<?php
										} else {
											?>
											<a id="delete-listing" data-listing-id="<?php echo esc_attr( $single_post->ID ); ?>" onclick="return confirm('Do you really want to delete this Listing?')" class="delete-listing button gray" href="javascript:void(0);"><i class="ti-close"></i></a>
											<?php
										}
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="fr-grid-footer">
							<?php if ( get_post_meta( $single_post->ID, 'resido_agents_agent_address', true ) ) { ?>
								<div class="fr-grid-footer-flex">
									<span class="fr-position"><i class="lni-map-marker"></i><?php echo get_post_meta( $single_post->ID, 'resido_agents_agent_address', true ); ?></span>
								</div>
							<?php } ?>
							<div class="fr-grid-footer-flex-right">
								<a href="<?php echo esc_url( get_permalink( $single_post->ID ) ); ?>" class="prt-view" tabindex="0"><?php esc_html_e( 'View', 'resido' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		} else {
			echo '<p class="messages-headline">';
			echo esc_html__( 'No agent found', 'resido' );
			echo '</p>';
		}
		?>
	</div>
</div>

<div class="agent-block dashboard-wraper form-submit">
	<h2><?php echo esc_html( 'Add Agent' ); ?></h2>
	<form action="#" method="post">
		<div class="row">
			<div class="form-group col-md-3">
				<label><?php echo esc_html( 'Title' ); ?></label>
				<input type="text" name="agent_title" class="form-control">
			</div>
			<div class="form-group col-md-3">
				<label><?php echo esc_html( 'Address' ); ?></label>
				<input type="text" name="agent_address" class="form-control">
			</div>
			<div class="form-group col-md-3">
				<label><?php echo esc_html( 'Cell' ); ?></label>
				<input type="text" name="agent_cell" class="form-control">
			</div>
			<div class="form-group col-md-3">
				<label><?php echo esc_html( 'Email' ); ?></label>
				<input type="text" name="agent_email" class="form-control">
			</div>
			<div class="form-group col-md-3">
				<label><?php echo esc_html( 'Content' ); ?></label>
				<textarea class="form-control" name="agent_content" id="" cols="30" rows="10"></textarea>
			</div>
			<div class="form-group col-md-3">
				<label><?php echo esc_html( 'Social Information' ); ?></label>
				<textarea class="form-control" name="agent_social" id="" cols="30" rows="10"></textarea>
			</div>
			<div class="form-group col-md-3">
				<label><?php echo esc_html( 'Agent Information' ); ?></label>
				<textarea class="form-control" name="agent_info" id="" cols="30" rows="10"></textarea>
			</div>
			<div class="form-group col-md-3">
				<label><?php echo esc_html( 'Featured Image' ); ?></label>
				<div class="row">
					<img id="frontend-image" src="#" alt="img" class="gallary_iamge_with" />
				</div>
				<input id="frontend-button" name="frontend-button" class="frontend-btn" type="file">
				<label for="frontend-button" class="drop_img_lst dropzone dz-clickable" id="single-logo">
					<i class="ti-gallery"></i>
					<span class="dz-default dz-message">
						<?php echo esc_html__( 'Drop files here to upload', 'resido' ); ?>
					</span>
				</label>
				<input id="frontend_rlfeaturedimg" name="frontend_rlfeaturedimg" type="hidden" value="" />
			</div>
		</div>
		<input class="btn btn-theme-light-2 rounded" type="submit" name="agentsubmit" value="<?php _e( 'Add agent', 'resido' ); ?>">
	</form>
</div>
