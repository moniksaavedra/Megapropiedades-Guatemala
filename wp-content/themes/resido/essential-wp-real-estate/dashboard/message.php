<div class="dashboard-wraper">
  <div class="messages-container">
	<div class="messages-headline">
		<h4><?php _e( 'Message Inbox', 'resido' ); ?></h4>
	</div>
	<div class="messages-inbox">
	  <?php
		global $wpdb;
		$table_name      = $wpdb->prefix . 'enquiry_message';
		$enquiry_message = $wpdb->get_results(
			"SELECT * FROM {$wpdb->prefix}enquiry_message WHERE created_for =" . get_current_user_id() . ' ORDER BY created_at ASC'
		);
		if ( ! empty( $enquiry_message ) ) {
			?>
		<div class="dash_message">
			<?php
			foreach ( $enquiry_message as $key => $single ) {
				$created_at = $single->created_at;
				$created_at = strtotime( $created_at );
				?>
			<div class="dash_message_portion message_display<?php echo esc_attr( $single->id ); ?>">
			  <div class="user row">
				<div class="message-avatar col-lg-2">
				  <img src="https://cdn-icons-png.flaticon.com/512/456/456212.png" alt="img">
				</div>
				<div class="message-by col-lg-10">
				  <div class="message-by-headline">
					<h5><?php echo esc_html( $single->name ); ?></h5>
					<div class="user_email"><?php echo esc_html( $single->email ); ?></div>
					<div class="user_phone"><?php echo esc_html( $single->phone ); ?></div>
				  </div>
				  <p><?php echo esc_html( $single->message ); ?></p>
				</div>
			  </div>
			  <div class="userdeletebtn">
				<span><?php echo esc_html( human_time_diff( $created_at, current_time( 'timestamp' ) ) ) . ' ago'; ?></span>
				<a onclick="return confirm('Do you really want to delete this Message?')" data-message-id="<?php echo esc_attr( $single->id ); ?>" class="delete-message button" href="javascript:void(0)"><i class="ti-trash"></i><?php echo esc_html( 'Delete' ); ?></a>
			  </div>
			</div>
				<?php
			}
			?>
		</div>
			<?php
		} else {
			echo '<p class="messages-headline">' . __( 'No message found', 'resido' ) . '</p>';
		}
		?>
	</div>
  </div>
</div>
