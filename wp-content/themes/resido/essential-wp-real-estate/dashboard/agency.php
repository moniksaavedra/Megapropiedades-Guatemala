<?php
$current_user = wp_get_current_user();
if ( isset( $_POST['ragencysubmit'] ) && $_POST['ragencysubmit'] ) {
	$post_arr = array(
		'post_title'   => $_POST['agency_title'],
		'post_content' => $_POST['agency_content'],
		'post_type'    => 'listing_agencies',
		'post_status'  => 'publish',
		'post_author'  => get_current_user_id(),
		'meta_input'   => array(
			'resido_agencies_agency_address'     => $_POST['agency_address'],
			'resido_agencies_agency_cell'        => $_POST['agency_cell'],
			'resido_agencies_agency_email'       => $_POST['agency_email'],
			'resido_agencies_agency_social'      => $_POST['agency_social'],
			'resido_agencies_agency_information' => $_POST['agency_info'],
		),
	);
	$post_id  = wp_insert_post( $post_arr );
	set_post_thumbnail( $post_id, $_POST['frontend_rlfeaturedimg'] );
}
?>
<div class="agency-block dashboard-wraper form-submit">
  <div class="row">
	<h2><?php esc_html_e( 'Agency List', 'resido' ); ?></h2>
	<?php
	$args               = array(
		'author'         => $current_user->ID,
		'post_type'      => 'listing_agencies',
		'post_status'    => array( 'publish', 'pending' ),
		'orderby'        => 'post_date',
		'order'          => 'DESC',
		'posts_per_page' => -1, // no limit,
	);
	$current_user_posts = get_posts( $args );
	if ( ! empty( $current_user_posts ) ) {
		foreach ( $current_user_posts as $single_post ) {
			$address  = get_post_meta( $single_post->ID, 'resido_agencies_agency_address', true );
			$comments = get_comments( array( 'post_id' => $single_post->ID ) );
			?>
		<!-- Agency -->
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
				  <h5 class="fr-can-name"><a href="<?php echo site_url( 'dashboard/?dashboard=agency&editagency=' . $single_post->ID ); ?>"><?php echo esc_html( $single_post->post_title ); ?></a></h5>
				</div>
				<div class="fr-grid-deatil-flex-right">
				  <div class="agent-email">
					<a href="<?php echo site_url( 'dashboard/?dashboard=agency&editagency=' . $single_post->ID ); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="ti-pencil"></i></a>
					<?php
					if ( current_user_can( 'administrator' ) ) {
						?>
					  <a onclick="return confirm('Do you really want to delete this Listing?')" href="<?php echo esc_url( get_delete_post_link( $single_post->ID ) ); ?>" data-toggle="tooltip" data-placement="top" title="Delete Property" class="delete"><i class="ti-close"></i></a>
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
				<?php if ( get_post_meta( $single_post->ID, 'resido_agencies_agency_address', true ) ) { ?>
				<div class="fr-grid-footer-flex">
				  <span class="fr-position"><i class="lni-map-marker"></i><?php echo get_post_meta( $single_post->ID, 'resido_agencies_agency_address', true ); ?></span>
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
		echo esc_html__( 'No agency found', 'resido' );
		echo '</p>';
	}
	?>
  </div>
</div>
<div class="agency-block dashboard-wraper form-submit">
  <h2><?php esc_html_e( 'Add Agency', 'resido' ); ?></h2>
  <form action="#" method="post">
	<div class="row">
	  <div class="form-group col-md-3">
		<label><?php echo esc_html__( 'Title', 'resido' ); ?></label>
		<input type="text" name="agency_title" class="form-control">
	  </div>
	  <div class="form-group col-md-3">
		<label><?php echo esc_html__( 'Address', 'resido' ); ?></label>
		<input type="text" name="agency_address" class="form-control">
	  </div>
	  <div class="form-group col-md-3">
		<label><?php echo esc_html__( 'Cell', 'resido' ); ?></label>
		<input type="text" name="agency_cell" class="form-control">
	  </div>
	  <div class="form-group col-md-3">
		<label><?php echo esc_html__( 'Email', 'resido' ); ?></label>
		<input type="text" name="agency_email" class="form-control">
	  </div>
	  <div class="form-group col-md-3">
		<label><?php echo esc_html__( 'Content', 'resido' ); ?></label>
		<textarea class="form-control" name="agency_content" id="" cols="30" rows="10"></textarea>
	  </div>
	  <div class="form-group col-md-3">
		<label><?php echo esc_html__( 'Social Information', 'resido' ); ?></label>
		<textarea class="form-control" name="agency_social" id="" cols="30" rows="10"></textarea>
	  </div>
	  <div class="form-group col-md-3">
		<label><?php echo esc_html__( 'Agency Information', 'resido' ); ?></label>
		<textarea class="form-control" name="agency_info" id="" cols="30" rows="10"></textarea>
	  </div>
	  <div class="form-group col-md-3">
		<label><?php echo esc_html__( 'Featured Image', 'resido' ); ?></label>
		<div class="row">
		  <img id="frontend-image" src="#" alt="img" class="gallary_iamge_with" />
		</div>
		<input id="frontend-button" name="frontend-button" class="frontend-btn" type="file">
		<label for="frontend-button" class="drop_img_lst dropzone dz-clickable" id="single-logo">
		  <i class="ti-gallery"></i>
		  <span class="dz-default dz-message">
			<?php esc_html_e( 'Drop files here to upload', 'resido' ); ?>
		  </span>
		</label>
		<input id="frontend_rlfeaturedimg" name="frontend_rlfeaturedimg" type="hidden" value="" />
	  </div>
	</div>
	<input class="btn btn-theme-light-2 rounded" type="submit" name="ragencysubmit" value="<?php esc_attr_e( 'Add agency', 'resido' ); ?>">
  </form>
</div>
