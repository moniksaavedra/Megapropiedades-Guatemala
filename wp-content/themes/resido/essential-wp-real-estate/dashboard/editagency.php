<?php
$editpostid = get_query_var( 'editagency' );

if ( isset( $_POST['ragencyupdate'] ) && $_POST['ragencyupdate'] ) {

	 $ra_post_title = sanitize_text_field( $_POST['agent_title'] );

	 $post_arr = array(
		 'ID'           => $editpostid,
		 'post_title'   => $_POST['agent_title'],
		 'post_content' => $_POST['agent_content'],
		 'post_type'    => 'listing_agencies',
		 'post_status'  => 'publish',
		 'post_author'  => get_current_user_id(),
	 );
	 wp_update_post( $post_arr );
	 $post_id = $editpostid;
	 set_post_thumbnail( $post_id, $_POST['frontend_rlfeaturedimg'] );

	 if ( isset( $_POST['agent_address'] ) && ! empty( $_POST['agent_address'] ) ) {
		 update_post_meta( $editpostid, 'resido_agencies_agency_address', $_POST['agent_address'] );
	 }
	 if ( isset( $_POST['agent_cell'] ) && ! empty( $_POST['agent_cell'] ) ) {
		 update_post_meta( $editpostid, 'resido_agencies_agency_cell', $_POST['agent_cell'] );
	 }
	 if ( isset( $_POST['agent_email'] ) && ! empty( $_POST['agent_email'] ) ) {
		 update_post_meta( $editpostid, 'resido_agencies_agency_email', $_POST['agent_email'] );
	 }
	 if ( isset( $_POST['agent_social'] ) && ! empty( $_POST['agent_social'] ) ) {
		 update_post_meta( $editpostid, 'resido_agencies_agency_social', $_POST['agent_social'] );
	 }
	 if ( isset( $_POST['agent_info'] ) && ! empty( $_POST['agent_info'] ) ) {
		 update_post_meta( $editpostid, 'resido_agencies_agency_information', $_POST['agent_info'] );
	 }

	 $post_id           = $editpostid;
	 $listing_menu_item = array();
}
	$current_post = get_post( $editpostid );

?>
 <!-- =========================== Add Form Start ============================================ -->
 <div class="agency-block row">
	 <div class="col-lg-12 col-sm-12">
		 <div class="dashboard-wraper form-submit">
			 <form action="" method="post">
				 <div class="row">
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Title' ); ?></label>
						 <input type="text" name="agent_title" class="form-control" value="<?php echo esc_attr( $current_post->post_title ); ?>">
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Address' ); ?></label>
						 <input type="text" name="agent_address" class="form-control" value="<?php echo esc_attr( $current_post->resido_agencies_agency_address ); ?>">
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Cell' ); ?></label>
						 <input type="text" name="agent_cell" class="form-control" value="<?php echo esc_attr( $current_post->resido_agencies_agency_cell ); ?>">
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Email' ); ?></label>
						 <input type="text" name="agent_email" class="form-control" value="<?php echo esc_attr( $current_post->resido_agencies_agency_email ); ?>">
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Content' ); ?></label>
						 <textarea class="form-control" name="agent_content" id="" cols="30" rows="10"><?php echo wp_kses_post( $current_post->post_content ); ?></textarea>
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Social Information' ); ?></label>
						 <textarea class="form-control" name="agent_social" id="" cols="30" rows="10"><?php echo wp_kses_post( $current_post->resido_agencies_agency_social ); ?></textarea>
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Agency Information' ); ?></label>
						 <textarea class="form-control" name="agent_info" id="" cols="30" rows="10"><?php echo wp_kses_post( $current_post->resido_agencies_agency_information ); ?></textarea>
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Featured Image' ); ?></label>
						 <div class="row">
							 <img id="frontend-image" src="<?php echo get_the_post_thumbnail_url( $current_post ); ?>" alt="img" class="gallary_iamge_with" />
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
				 <input class="btn btn-theme-light-2 rounded" type="submit" name="ragencyupdate" value="<?php _e( 'Update', 'resido' ); ?>">
			 </form>
		 </div>
	 </div>
 </div>
 <!-- =========================== Add Form End ============================================ -->
 <div class="agency-block dashboard-wraper form-submit">
	 <div class="row">
		 <?php
			$args = array(
				'post_type'      => 'ragents',
				'post_status'    => array( 'publish' ),
				'posts_per_page' => -1, // no limit,
				'meta_query'     => array(
					array(
						'key'     => 'rlisting_parent_agency',
						'value'   => $editpostid,
						'compare' => '=',
					),

				),
			);
			$current_agent_posts = get_posts( $args );
			if ( ! empty( $current_agent_posts ) ) {
				foreach ( $current_agent_posts as $single_post ) {
					$address  = get_post_meta( $single_post->ID, 'rlisting_address', true );
					$comments = get_comments( array( 'post_id' => $single_post->ID ) );
					$price    = get_post_meta( $single_post->ID, 'rlisting_sale_or_rent', true );
					$postfix  = get_post_meta( $single_post->ID, 'rlisting_price_postfix', true );
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
									 <h5 class="fr-can-name"><a href="<?php echo get_permalink( $single_post->ID ); ?>"><?php echo esc_html( $single_post->post_title ); ?></a></h5>
								 </div>
								 <div class="fr-grid-deatil-flex-right">
									 <div class="agent-email">
										 <a href="<?php echo site_url( 'dashboard/?dashboard=agent&editagent=' . $single_post->ID ); ?>" data-toggle="tooltip" data-placement="top" title="Edit"><i class="ti-pencil"></i></a>
										 <?php
											if ( current_user_can( 'administrator' ) ) {
												?>
											 <a onclick="return confirm('Do you really want to delete this Agent?')" href="<?php echo get_delete_post_link( $single_post->ID ); ?>" data-toggle="tooltip" data-placement="top" title="Delete Property" class="delete"><i class="ti-close"></i></a>
												<?php
											} else {
												?>
											 <a id="delete-listing" data-listing-id="<?php echo esc_attr( $single_post->ID ); ?>" onclick="return confirm('Do you really want to delete this Agent?')" class="delete-listing button gray" href="javascript:void(0);"><i class="ti-close"></i></a>
												<?php
											}
											?>
									 </div>
								 </div>
							 </div>

						 </div>

						 <div class="fr-grid-footer">
							 <?php if ( get_post_meta( $single_post->ID, 'rlisting_agent_address', true ) ) { ?>
								 <div class="fr-grid-footer-flex">
									 <span class="fr-position"><i class="lni-map-marker"></i><?php echo get_post_meta( $single_post->ID, 'rlisting_agent_address', true ); ?></span>
								 </div>
							 <?php } ?>

							 <div class="fr-grid-footer-flex-right">
								 <a href="<?php echo get_permalink( $single_post->ID ); ?>" class="prt-view" tabindex="0">View</a>
							 </div>
						 </div>

					 </div>
				 </div>

					<?php
				}
			}
			?>
	 </div>
	 <a class="btn btn-theme-light-2 rounded" href="<?php echo site_url( 'dashboard/?dashboard=agent' ); ?>"><?php _e( 'Add Agent', 'resido' ); ?></a>
 </div>
