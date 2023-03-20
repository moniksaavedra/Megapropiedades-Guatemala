<div class="dashboard-wraper"> 
<?php
if ( ! is_user_logged_in() ) {
	echo '<p>' . esc_html__( 'Please', 'resido' ) . ' <a href="' . esc_url( get_page_link( cl_admin_get_option( 'login_redirect_page' ) ) ) . '">' . esc_html__( 'Login', 'resido' ) . '</a></p>';
} else {
	wp_enqueue_media();
	global $current_user;
	$user_id = $current_user->ID;
	$error   = array();
	$alert   = array();
	if ( isset( $_POST['submit'] ) ) {

		$email       = sanitize_email( $_POST['email'] );
		$phone       = cl_sanitization( $_POST['phone'] );
		$address     = cl_sanitization( $_POST['address'] );
		$city        = cl_sanitization( $_POST['city'] );
		$state       = cl_sanitization( $_POST['state'] );
		$zip         = cl_sanitization( $_POST['zip'] );
		$description = cl_sanitization( $_POST['description'] );
		$facebook    = cl_sanitization( $_POST['facebook'] );
		$twitter     = cl_sanitization( $_POST['twitter'] );
		$linkedin    = cl_sanitization( $_POST['linkedin'] );
		$instagram   = cl_sanitization( $_POST['instagram'] );


		if ( count( $error ) == 0 ) {
			$userdata = array(
				'ID'          => $user_id,
				'user_email'  => $email,
				'phone'       => $phone,
				'address'     => $address,
				'city'        => $city,
				'state'       => $state,
				'zip'         => $zip,
				'description' => $description,
				'facebook'    => $facebook,
				'twitter'     => $twitter,
				'linkedin'    => $linkedin,
				'instagram'   => $instagram,
			);
			// Update user information
			$user_update = wp_update_user( $userdata );

			// Update User avatar
			if ( isset( $_POST['user_avt'] ) && ! empty( $_POST['user_avt'] ) ) {
				update_user_meta( $user_id, 'wp_user_avatar', cl_sanitization( $_POST['user_avt'] ) );
			}
			// Check if theres any error else return success
			if ( ! is_wp_error( $user_update ) ) {
				$alert['class'] = 'success';
				$alert['msg']   = esc_html__( 'Profile Successfully Updated.', 'resido' );
			}
		} else {
			$alert['class'] = 'danger';
			$alert['msg']   = $error['error_msg'];
		}
	} ?>
	<form action="#" method="post" id="cl-update-user-form" class="cl-update-user-form form-submit">
		<div class="container">
			<h4 class="col-md-12"><?php esc_html_e('My Account','resido');?></h4>
			<div class="row">
				<div class="col-md-12 form-group cl_user_avatar">
					<label><?php esc_html_e('Change Avatar','resido');?></label>
					<?php
					$avatar_url = cl_get_avatar_url();
					if ( $avatar_url ) {
						echo '<img class="files_featured" width="90px"  height="90px" src="' . esc_url( $avatar_url ) . '" alt="img" />';
					} else {
						echo '<img class="files_featured" src="#" alt="img" />';
					}
					?>
					<label for="file-input" class="frontend-avatar select_single_label">
						<i class="fa fa-upload"></i><?php esc_html_e( ' Select Image', 'resido' ); ?>
					</label>
					<input class="single_img_id" name="user_avt" type="hidden" />
				</div>
				<div class="col-md-6 form-group">
					<label for="user_name"><?php esc_html_e( 'User Name', 'resido' ); ?></label>
					<input required type="text" name="user_name" id="user_name" class="input form-control" value="<?php echo esc_attr( $current_user->data->user_login ); ?>" />
				</div>
				<div class="col-md-6 form-group">
					<label for="email"><?php esc_html_e( 'E-mail', 'resido' ); ?></label>
					<input required readonly type="text" name="email" id="email" class="input form-control" value="<?php echo esc_attr( $current_user->data->user_email ); ?>" />
				</div>
				<?php
				$package_args  = array(
					'post_type'      => 'cl_payment',
					'posts_per_page' => 1,
					'meta_query'     => array(
						array(
							'key'   => '_cl_payment_user_email',
							'value' => $current_user->data->user_email,
						),
					),
				);
				$package_query = new \WP_Query( $package_args );
				?>
				<div class="col-md-6 form-group">
					<label><?php echo esc_html__( 'Current Subscription', 'resido' ); ?></label>
					<select name='current_subscription' class="form-control" disabled>
					<?php
					if ( $package_query->posts ) {
						foreach ( $package_query->posts as $key => $post ) {
							$package_name = get_post_meta( $post->ID, '_cl_payment_meta', true );
							echo '<option value="' . $package_name['cart_details'][0]['id'] . '">' . $package_name['cart_details'][0]['name'] . '</option>';
						}
					} else {
						?>
						<option value=""><?php echo esc_html__( 'No Subscription', 'resido' ); ?></option>
						<?php
					}
					?>
					</select>
				</div>
				<div class="col-md-6 form-group">
					<?php $phone = get_user_meta( $current_user->ID, 'phone', true ); ?>
					<label for="phone"><?php esc_html_e( 'Phone', 'resido' ); ?></label>
					<input required type="text" name="phone" id="phone" class="input form-control" value="<?php echo esc_attr( $phone ); ?>" />
				</div>
				<div class="col-md-6 form-group">
					<?php $address = get_user_meta( $current_user->ID, 'address', true ); ?>
					<label for="address"><?php esc_html_e( 'Address', 'resido' ); ?></label>
					<input required type="text" name="address" id="address" class="input form-control" value="<?php echo esc_attr( $address ); ?>" />
				</div>
				<div class="col-md-6 form-group">
					<?php $city = get_user_meta( $current_user->ID, 'city', true ); ?>
					<label for="city"><?php esc_html_e( 'City', 'resido' ); ?></label>
					<input required type="text" name="city" id="city" class="input form-control" value="<?php echo esc_attr( $city ); ?>" />
				</div>
				<div class="col-md-6 form-group">
					<?php $state = get_user_meta( $current_user->ID, 'state', true ); ?>
					<label for="state"><?php esc_html_e( 'State', 'resido' ); ?></label>
					<input required type="text" name="state" id="state" class="input form-control" value="<?php echo esc_attr( $state ); ?>" />
				</div>
				<div class="col-md-6 form-group">
					<?php $zip = get_user_meta( $current_user->ID, 'zip', true ); ?>
					<label for="zip"><?php esc_html_e( 'Zip', 'resido' ); ?></label>
					<input required type="text" name="zip" id="zip" class="input form-control" value="<?php echo esc_attr( $zip ); ?>" />
				</div>
				<div class="col-md-12 form-group">
					<?php
					$description = get_user_meta( $current_user->ID, 'description', true );
					?>
					<label for="description"><?php echo esc_html__( 'About', 'resido' ); ?></label>
					<textarea name="description" id="description" class="input form-control"><?php echo esc_attr( $description ); ?></textarea>
				</div>
				<h4 class="col-md-12">Social Accounts</h4>
				<div class="submit-section col-md-12">
					<div class="row">

						<div class="col-md-6 col-lg-6 form-group">
							<?php
							$facebook = get_user_meta( $current_user->ID, 'facebook', true );
							?>
							<label for="facebook"><i class="lab la-facebook-f"></i><?php echo esc_html__( 'Facebook', 'resido' ); ?></label>
							<input type="text" name="facebook" id="facebook" class="input form-control" value="<?php echo esc_attr( $facebook ); ?>" />
						</div>
						<div class="col-md-6 col-lg-6 form-group">
							<?php
							$twitter = get_user_meta( $current_user->ID, 'twitter', true );
							?>
							<label for="twitter"><?php echo esc_html__( 'Twitter', 'resido' ); ?></label>
							<input type="text" name="twitter" id="twitter" class="input form-control" value="<?php echo esc_attr( $twitter ); ?>" />
						</div>
						<div class="col-md-6 col-lg-6 form-group">
							<?php
							$linkedin = get_user_meta( $current_user->ID, 'linkedin', true );
							?>
							<label for="linkedin"><?php echo esc_html__( 'Linkedin', 'resido' ); ?></label>
							<input type="text" name="linkedin" id="linkedin" class="input form-control" value="<?php echo esc_attr( $linkedin ); ?>" />
						</div>
						<div class="col-md-6 col-lg-6 form-group">
							<?php
							$instagram = get_user_meta( $current_user->ID, 'instagram', true );
							?>
							<label for="instagram"><?php echo esc_html__( 'Instagram', 'resido' ); ?></label>
							<input type="text" name="instagram" id="instagram" class="input form-control" value="<?php echo esc_attr( $instagram ); ?>" />
						</div>
					</div>
				</div>
				<?php if ( ! empty( $alert ) ) { ?>
					<div class="col-md-12">
						<div class="alert alert-<?php echo esc_attr( $alert['class'] ); ?>">
							<?php echo esc_html( $alert['msg'] ); ?>
						</div>
					</div>
				<?php } ?>
				<div class="col-md-12 form-group">
					<button type="submit" name="submit" class="btn btn-theme"><?php esc_html_e( 'Save Changes', 'resido' ); ?></button>
				</div>
			</div>
		</div>
	</form>
	<?php
}
?>
</div>
