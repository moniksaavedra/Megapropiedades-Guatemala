<?php
function resido_custom_query_var( $vars ) {
	$vars[] = 'editlisting';
	$vars[] = 'map_var';
	$vars[] = 'editagency';
	$vars[] = 'editagent';
	return $vars;
}
add_filter( 'query_vars', 'resido_custom_query_var' );

function resido_register_query_vars( $vars ) {
	$vars[] = 'dashboard';
	$vars[] = 'layout';
	return $vars;
}
add_filter( 'query_vars', 'resido_register_query_vars' );

function resido_show_permalink( $post_link, $post ) {
	if ( is_object( $post ) && $post->post_type == 'rlisting' ) {
		$terms = wp_get_object_terms( $post->ID, 'rlisting_category' );
		if ( $terms ) {
			return str_replace( '%rlisting_category%', $terms[0]->slug, $post_link );
		}
	}
	return $post_link;
}
add_filter( 'post_type_link', 'resido_show_permalink', 1, 2 );

// Add the custom columns to the book post type:
// add_filter('manage_rlisting_posts_columns', 'set_custom_edit_book_columns');
function set_custom_edit_book_columns( $columns ) {
	unset( $columns['date'] );
	unset( $columns['tags'] );
	$columns['category'] = __( 'Category', 'resido-core' );
	$columns['author']   = __( 'Author', 'resido-core' );
	$columns['featured'] = __( 'Featured', 'resido-core' );
	$columns['verified'] = __( 'Verified', 'resido-core' );
	$columns['package']  = __( 'Package', 'resido-core' );
	$columns['date']     = __( 'Date', 'resido-core' );
	return $columns;
}

// add_action( 'manage_rlisting_posts_custom_column', 'custom_book_column', 10, 2 );
function custom_book_column( $column, $post_id ) {
	switch ( $column ) {

		case 'category':
			$terms = get_the_terms( $post_id, 'rlisting_category' );
			if ( $terms && ! empty( $terms ) ) {
				echo $terms[0]->name;
			}
			break;

		case 'featured':
			$featured = get_post_meta( $post_id, 'featured', true );
			if ( $featured ) {
				echo '<a href="javascript:void(0)" id="f_added_' . $post_id . '" class="button set-lfeatured" data-id="' . $post_id . '">
            <span class="lfeatured">Featured</span>
            </a>';
			} else {
				echo '<a href="javascript:void(0)" id="f_added_' . $post_id . '" class="button set-lfeatured" data-id="' . $post_id . '">
            <span class="as-lfeatured">Set as featured</span>
            </a>';
			}
			break;

		case 'verified':
			$verified = get_post_meta( $post_id, 'varified', true );
			if ( $verified ) {
				echo '<a href="#" class="button set-lverified lverified" id="v_added_' . $post_id . '" data-id="' . $post_id . '">
            <span class="lverified">' . __( 'Verified', 'resido-core' ) . '</span>
            </a>';
			} else {
				echo '<a href="#" class="button set-lverified lverified" id="v_added_' . $post_id . '"  data-id="' . $post_id . '">
            <span class="as-lverified">' . __( 'Verify', 'resido-core' ) . '</span>
            </a>';
			}
			break;
		case 'package':
			$user_package_id = get_post_meta( $post_id, 'user_package_id', true );
			if ( $user_package_id ) {
				if ( 'publish' == get_post_status( $user_package_id ) ) {
					echo __( $user_package_id, 'resido-core' );
				} else {
					echo __( 'Expired', 'resido-core' );
				}
			} else {
				echo __( 'Set Expired by Admin', 'resido-core' );
			}
			break;
	}
}

function category_fields_new( $taxonomy ) {
	// Function has one field to pass - Taxonomy ( Category in this case )
	wp_nonce_field( 'category_meta_new', 'category_meta_new_nonce' ); // Create a Nonce so that we can verify the integrity of our data
	?>
	<div class="form-field">
		<label for="category_fa">Font-Awesome Icon Class</label>
		<input name="category_icon" id="category_fa" type="text" value="" style="width:100%" />
		<p class="description">Enter a custom font-awesome icon - <a href="http://fontawesome.io/icons/" target="_blank">List
				of Icons</a></p>
	</div>
	<?php
}
// add_action( 'rlisting_category_add_form_fields', 'category_fields_new', 10 );

/**
 * Category "Edit Term" Page - Add Additional Field(s)
 *
 * @param Object $term
 * @param string $taxonomy
 */
function category_fields_edit( $term, $taxonomy ) {
	// Function has one field to pass - Term ( term object) and Taxonomy ( Category in this case )
	wp_nonce_field( 'category_meta_edit', 'category_meta_edit_nonce' ); // Create a Nonce so that we can verify the integrity of our data
	$category_icon = get_option( "{$taxonomy}_{$term->term_id}_icon" ); // Get the icon if one is set already
	?>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="category_fa">Font-Awesome Icon Class</label>
		</th>
		<td>
			<input name="category_icon" id="category_fa" type="text" value="<?php echo ( ! empty( $category_icon ) ) ? $category_icon : ''; ?>" style="width:100%;" />
			<!-- IF `$category_icon` is not empty, display it. Otherwise display an empty string -->
			<p class="description">Enter a custom Font-Awesome icon - <a href="http://fontawesome.io/icons/" target="_blank">List of Icons</a></p>
		</td>
	</tr>
	<?php
}
// add_action( 'rlisting_category_edit_form_fields', 'category_fields_edit', 10, 2 );

/**
 * Save our Additional Taxonomy Fields
 *
 * @param int $term_id
 */
function save_category_fields( $term_id ) {

	/** Verify we're either on the New Category page or Edit Category page using Nonces */
	/** Verify that a Taxonomy is set */
	if (
		isset( $_POST['taxonomy'] ) && isset( $_POST['category_icon'] ) &&
		( isset( $_POST['category_meta_new_nonce'] ) && wp_verify_nonce( $_POST['category_meta_new_nonce'], 'category_meta_new' ) || // Verify our New Term Nonce
			isset( $_POST['category_meta_edit_nonce'] ) && wp_verify_nonce( $_POST['category_meta_edit_nonce'], 'category_meta_edit' ) // Verify our Edited Term Nonce
		)
	) {
		$taxonomy      = $_POST['taxonomy'];
		$category_icon = get_option( "{$taxonomy}_{$term_id}_icon" ); // Grab our icon if one exists
		if ( ! empty( $_POST['category_icon'] ) ) { // IF the user has entered text, update our field.
			update_option( "{$taxonomy}_{$term_id}_icon", htmlspecialchars( sanitize_text_field( $_POST['category_icon'] ) ) ); // Sanitize our data before adding to the database
		} elseif ( ! empty( $category_icon ) ) { // Category Icon IS empty but the option is set, they may not want an icon on this category
			delete_option( "{$taxonomy}_{$term_id}_icon" ); // Delete our option
		}
	} // Nonce Conditional
} // End Function
// add_action( 'created_rlisting_category', 'save_category_fields' );
// add_action( 'edited_rlisting_category', 'save_category_fields' );

/**
 * Essential Cleanup of our Options
 *
 * @param int    $term_id
 * @param string $taxonomy
 */
function remove_term_options( $term_id, $taxonomy ) {
	delete_option( "{$taxonomy}_{$term_id}_icon" ); // Delete our option
}
add_action( 'pre_delete_term', 'remove_term_options', 10, 2 );

add_action( 'user-dashboard-menu', 'resido_user_dashboard_menu' );
function resido_user_dashboard_menu() {
	$current_user = wp_get_current_user();
	$avatar_url   = cl_get_avatar_url();
	?>
	<li class="login-attri">
		<div class="btn-group account-drop">
			<button type="button" class="btn btn-order-by-filt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<img src="<?php echo esc_url( $avatar_url ); ?>" class="avater-img" alt="img">
				<?php echo $current_user->display_name; ?>
			</button>
			<div class="dropdown-menu pull-right animated flipInX">
				<a href="<?php echo site_url( 'dashboard' ); ?>"><i class="ti-dashboard"></i><?php echo esc_html__( 'Dashboard', 'resido-core' ); ?></a>
				<a href="<?php echo site_url( 'add-listing' ); ?>"><i class="ti-plus"></i><?php echo esc_html__( 'Add Listing', 'resido-core' ); ?></a>
				<a href="<?php echo site_url( 'dashboard/?dashboard=agency' ); ?>"><i class="ti-home"></i><?php echo esc_html__( 'Agency', 'resido-core' ); ?></a>
				<a href="<?php echo site_url( 'dashboard/?dashboard=profile' ); ?>"><i class="ti-user"></i><?php echo esc_html__( 'My Profile', 'resido-core' ); ?></a>
				<a href="<?php echo site_url( 'dashboard/?dashboard=listings' ); ?>"><i class="ti-layers"></i><?php echo esc_html__( 'My Listing', 'resido-core' ); ?></a>
				<a href="<?php echo site_url( 'dashboard/?dashboard=bookmarked' ); ?>"><i class="ti-bookmark"></i><?php echo esc_html__( 'Bookmarked', 'resido-core' ); ?></a>
				<a class="active" href="<?php echo site_url( 'dashboard/?dashboard=changepassword' ); ?>">
					<i class="ti-unlock"></i>
					<?php _e( 'Change Password', 'resido-core' ); ?>
				</a>
				<a href="<?php echo wp_logout_url( home_url() ); ?>"><i class="ti-power-off"></i>
					<?php _e( 'Log Out', 'resido-core' ); ?></a>
			</div>
		</div>
	</li>
	<?php
}

add_action( 'after_setup_theme', 'resido_remove_admin_bar' );
function resido_remove_admin_bar() {
	if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
		show_admin_bar( false );
	}
}

function resido_user_redirect() {
	if ( is_admin() && ! current_user_can( 'administrator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		wp_safe_redirect( home_url() );
		exit;
	}
}
add_action( 'admin_init', 'resido_user_redirect' );

function resido_footer_popup() {
	echo do_shortcode( '[listing-login-form]' );
	echo do_shortcode( '[listing-resistration-form]' );
	echo do_shortcode( '[listing-reset-form]' );
}
add_action( 'wp_footer', 'resido_footer_popup' );

add_filter( 'intermediate_image_sizes', 'resido_slider_image_sizes', 999 );
function resido_slider_image_sizes( $image_sizes ) {
	// size for slider
	// $slider_image_sizes = array('your_image_size_1', 'your_image_size_2');
	$slider_image_sizes = array( 'gallery_image_size' );
	// for ex: $slider_image_sizes = array( 'thumbnail', 'medium' );
	// instead of unset sizes, return your custom size for slider image
	if ( isset( $_REQUEST['post_id'] ) && 'rlisting' === get_post_type( $_REQUEST['post_id'] ) ) {
		return $slider_image_sizes;
	}
	return $image_sizes;
}

// to create a custom size you can use this:
add_image_size( 'gallery_image_size', 100, 100, false ); // Crop mode
add_image_size( 'rlisting_home_size', 332, 235, true );
add_image_size( 'rlisting_home_size_1', 370, 262, true );
add_image_size( 'rlisting_list', 289, 210, true );
add_image_size( 'rlisting_list_full', 439, 210, true );
add_image_size( 'rlisting_map', 394, 210, true );
add_image_size( 'rlisting_map_grid2', 480, 210, true );

function theme_xyz_header_metadata() {
	?>
	<meta name="abc" content="<?php the_title(); ?>" />
	<?php

}
// add_action('wp_head', 'theme_xyz_header_metadata');
function tn_disable_visual_editor( $can ) {

	if ( ( is_admin() ) ) {
		return true;
	} else {
		return false;
	}
}
add_filter( 'user_can_richedit', 'tn_disable_visual_editor' );

add_action( 'pre_get_posts', 'resido_users_own_attachments' );
function resido_users_own_attachments( $wp_query_obj ) {
	global $current_user, $pagenow;
	$is_attachment_request = ( $wp_query_obj->get( 'post_type' ) == 'attachment' );
	if ( ! $is_attachment_request ) {
		return;
	}
	if ( ! is_a( $current_user, 'WP_User' ) ) {
		return;
	}
	if ( ! in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) ) {
		return;
	}
	if ( ! current_user_can( 'delete_pages' ) ) {
		$wp_query_obj->set( 'author', $current_user->ID );
	}
	return;
}




add_filter( 'cl_add_listing_form_after_filter', 'resido_add_field_add_listing_filter_func' );
function resido_add_field_add_listing_filter_func() {

	$agent_onoff  = resido_get_options( 'agent_onoff' );
	$agency_onoff = resido_get_options( 'agency_onoff' );

	$agentlist   = get_posts(
		array(
			'post_type'      => 'listing_agents',
			'posts_per_page' => -1,
		)
	);
	$agentarray  = array();
	$agencylist  = get_posts(
		array(
			'post_type'      => 'listing_agencies',
			'posts_per_page' => -1,
		)
	);
	$agencyarray = array();
	if ( ! empty( $agencylist ) && $agency_onoff == '1' ) {
		?>
	<div class="column form-group col-md-12">
		<label for="listing_agency"><?php esc_html_e( 'Agency', 'resido-core' ); ?></label>
		<select name="listing_agency" class="form-control">
		<?php
			echo '<option value="0">' . esc_html__( 'Select Agency', 'resido-core' ) . '</option>';
		foreach ( $agencylist as $agency ) {
			echo '<option value="' . $agencyarray[ $agency->ID ] . '">' . $agency->post_title . '</option>';
		}
		?>
		</select>
	</div>
		<?php
	}
	if ( ! empty( $agentlist ) && $agent_onoff == '1' ) {
		?>
	<div class="column form-group col-md-12">
		<label for="listing_agent"><?php esc_html_e( 'Agent', 'resido-core' ); ?></label>
		<select name="listing_agent" class="form-control">
		<?php
			echo '<option value="0">' . esc_html__( 'Select Agent', 'resido-core' ) . '</option>';
		foreach ( $agentlist as $agent ) {
			echo '<option value="' . $agentarray[ $agent->ID ] . '">' . $agent->post_title . '</option>';
		}
		?>
		</select>
	</div>
		<?php
	}
}

add_filter( 'cl_edit_listing_form_after_filter', 'resido_add_field_edit_listing_filter_func' );
function resido_add_field_edit_listing_filter_func() {
	$agentlist = get_posts(
		array(
			'post_type'      => 'listing_agents',
			'posts_per_page' => -1,
		)
	);

	$agencylist = get_posts(
		array(
			'post_type'      => 'listing_agencies',
			'posts_per_page' => -1,
		)
	);

	$editpostid = get_query_var( 'cl_edit_listing_var' );

	$listing_agent  = '';
	$listing_agency = '';

	if ( isset( $_POST['listing_agent'] ) && ! empty( $_POST['listing_agent'] ) ) {
		$listing_agent = sanitize_text_field( $_POST['listing_agent'] );
	}

	if ( isset( $_POST['listing_agency'] ) && ! empty( $_POST['listing_agency'] ) ) {
		$listing_agency = sanitize_text_field( $_POST['listing_agency'] );
	}

	update_post_meta( $editpostid, 'resido_listing_rlagencyinfo', $listing_agency );
	update_post_meta( $editpostid, 'resido_listing_rlagentinfo', $listing_agent );

	$get_listing_agency = get_post_meta( $editpostid, 'resido_listing_rlagencyinfo', true );
	$get_listing_agent  = get_post_meta( $editpostid, 'resido_listing_rlagentinfo', true );
	?>
	<div class="column form-group col-md-12">
		<label for="listing_agent"><?php esc_html_e( 'Agency', 'resido-core' ); ?></label>
		<select name="listing_agent" class="form-control">
		<?php
		if ( ! empty( $agencylist ) ) {
			echo '<option value="">' . esc_html__( 'Select Agency', 'resido-core' ) . '</option>';
			foreach ( $agencylist as $agency ) {
				if ( $get_listing_agent == $agency->ID ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
				echo '<option value="' . esc_attr( $agency->ID ) . '" ' . $selected . '>' . esc_html( $agency->post_title ) . '</option>';
			}
		}
		?>
		</select>
	</div>
	<div class="column form-group col-md-12">
		<label for="listing_agent"><?php esc_html_e( 'Agent', 'resido-core' ); ?></label>
		<select name="listing_agent" class="form-control">
		<?php
		if ( ! empty( $agentlist ) ) {
			echo '<option value="">' . esc_html__( 'Select Agent', 'resido-core' ) . '</option>';
			foreach ( $agentlist as $agent ) {
				if ( $get_listing_agency == $agent->ID ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
				echo '<option value="' . esc_attr( $agent->ID ) . '" ' . $selected . '>' . esc_html( $agent->post_title ) . '</option>';
			}
		}
		?>
		</select>
	</div>
	<?php
}

add_action( 'wp_ajax_resido-delete-message', 'resido_delete_message_from_dashboard' );
add_action( 'wp_ajax_nopriv_resido-delete-message', 'resido_delete_message_from_dashboard' );
function resido_delete_message_from_dashboard() {
	global $wpdb;
	$message_id = $_POST['message_id'];
	$return     = $wpdb->delete( $wpdb->prefix . 'enquiry_message', array( 'id' => $message_id ) );
	echo $return;
	die();
}
