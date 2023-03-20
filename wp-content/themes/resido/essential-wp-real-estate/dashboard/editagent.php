<?php
$editpostid = get_query_var( 'editagent' );

if ( isset( $_POST['ragentupdate'] ) && $_POST['ragentupdate'] ) {

	 $ra_post_title = sanitize_text_field( $_POST['agent_title'] );

	 $post_arr = array(
		 'ID'           => $editpostid,
		 'post_title'   => $_POST['agent_title'],
		 'post_content' => $_POST['agent_content'],
		 'post_type'    => 'listing_agents',
		 'post_status'  => 'publish',
		 'post_author'  => get_current_user_id(),
	 );
	 wp_update_post( $post_arr );
	 $post_id = $editpostid;
	 set_post_thumbnail( $post_id, $_POST['frontend_rlfeaturedimg'] );

	 if ( isset( $_POST['agent_address'] ) && ! empty( $_POST['agent_address'] ) ) {
		 update_post_meta( $editpostid, 'resido_agents_agent_address', $_POST['agent_address'] );
	 }
	 if ( isset( $_POST['agent_cell'] ) && ! empty( $_POST['agent_cell'] ) ) {
		 update_post_meta( $editpostid, 'resido_agents_agent_cell', $_POST['agent_cell'] );
	 }
	 if ( isset( $_POST['agent_email'] ) && ! empty( $_POST['agent_email'] ) ) {
		 update_post_meta( $editpostid, 'resido_agents_agent_email', $_POST['agent_email'] );
	 }
	 if ( isset( $_POST['agent_social'] ) && ! empty( $_POST['agent_social'] ) ) {
		 update_post_meta( $editpostid, 'resido_agents_agent_social', $_POST['agent_social'] );
	 }
	 if ( isset( $_POST['agent_info'] ) && ! empty( $_POST['agent_info'] ) ) {
		 update_post_meta( $editpostid, 'resido_agents_agent_information', $_POST['agent_info'] );
	 }

	 $post_id           = $editpostid;
	 $listing_menu_item = array();
}
	$current_post = get_post( $editpostid );

?>
 <div class="row">
	 <div class="col-lg-10 col-md-10 col-sm-12">
		 <div class="dashboard-wraper form-submit">
			 <form action="" method="post">
				 <div class="row">
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Title' ); ?></label>
						 <input type="text" name="agent_title" class="form-control" value="<?php echo esc_attr( $current_post->post_title ); ?>">
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Address' ); ?></label>
						 <input type="text" name="agent_address" class="form-control" value="<?php echo esc_attr( $current_post->resido_agents_agent_address ); ?>">
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Cell' ); ?></label>
						 <input type="text" name="agent_cell" class="form-control" value="<?php echo esc_attr( $current_post->resido_agents_agent_cell ); ?>">
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Email' ); ?></label>
						 <input type="text" name="agent_email" class="form-control" value="<?php echo esc_attr( $current_post->resido_agents_agent_email ); ?>">
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Content' ); ?></label>
						 <textarea class="form-control" name="agent_content" id="" cols="30" rows="10"><?php echo wp_kses_post( $current_post->post_content ); ?></textarea>
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Social Information' ); ?></label>
						 <textarea class="form-control" name="agent_social" id="" cols="30" rows="10"><?php echo wp_kses_post( $current_post->resido_agents_agent_social ); ?></textarea>
					 </div>
					 <div class="form-group col-md-3">
						 <label><?php echo esc_html( 'Agent Information' ); ?></label>
						 <textarea class="form-control" name="agent_info" id="" cols="30" rows="10"><?php echo wp_kses_post( $current_post->resido_agents_agent_information ); ?></textarea>
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
				 <input class="btn btn-theme-light-2 rounded" type="submit" name="ragentupdate" value="<?php _e( 'Update', 'resido' ); ?>">
			 </form>
		 </div>
	 </div>
 </div>
