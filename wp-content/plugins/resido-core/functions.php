<?php
function get_terms_by_taxonomy( $tax_name ) {
	return get_the_terms( get_the_ID(), $tax_name );
}

function listing_meta_field( $field_name ) {
	echo get_post_meta( get_the_ID(), $field_name, true );
}


function get_lsiting_featured() {
	$rlisting_features = get_terms(
		array(
			'taxonomy'   => 'rlisting_features',
			'hide_empty' => false,
		)
	);
	if ( ! empty( $rlisting_features ) ) {
		foreach ( $rlisting_features as $key => $single ) { ?>
			<li>
				<input id="rlisting_features<?php echo $key; ?>" class="rlisting_features checkbox-custom" name="rlisting_features[]" type="checkbox" value="<?php echo $single->slug; ?>">
				<label for="rlisting_features<?php echo $key; ?>" class="checkbox-custom-label"><?php echo $single->name; ?></label>
			</li>
			<?php
		}
	}
}

function resido_total_active_lingting_by_user() {
	global $current_user;
	$args               = array(
		'author'         => $current_user->ID,
		'post_type'      => 'rlisting',
		'posts_per_page' => -1, // no limit,
	);
	$current_user_posts = get_posts( $args );
	return count( $current_user_posts );
}

function resido_total_view() {
	global $current_user;
	$args = array(
		'author'         => $current_user->ID,
		'post_type'      => 'rlisting',
		'posts_per_page' => -1, // no limit,
	);

	$count              = 0;
	$current_user_posts = get_posts( $args );
	foreach ( $current_user_posts as $single_post ) {
		$single_count = get_post_meta( $single_post->ID, 'post_views_count', true );
		$count       += (int) $single_count;
	}

	return $count;
}

function resido_total_review() {
	global $current_user;
	$args = array(
		'author'         => $current_user->ID,
		'post_type'      => 'rlisting',
		'posts_per_page' => -1, // no limit,
	);

	$comments           = 0;
	$current_user_posts = get_posts( $args );
	foreach ( $current_user_posts as $single_post ) {
		$single_comments = get_comments( array( 'post_id' => $single_post->ID ) );
		$comments       += (int) count( $single_comments );
	}
	return $comments;
}

function resido_get_favarited_meta_value( $userid, $postid ) {
	return get_user_meta( $userid, '_favorite_posts' );
}

function resido_total_saved() {
	global $current_user;
	$user_meta = get_user_meta( $current_user->ID, '_favorite_posts' );
	echo count( $user_meta );
}

function resido_get_usermeta( $metafield ) {
	global $current_user;
	return get_user_meta( $current_user->ID, $metafield, true );
}

function resido_get_listing_meta( $id, $metafield ) {
	return get_post_meta( $id, $metafield, true );
}

function resido_get_agent_meta( $id, $metafield ) {
	return get_user_meta( $id, $metafield, true );
}

function add_custom_query_var( $vars ) {
	$vars[] = 'editlisting';
	$vars[] = 'map_var';
	$vars[] = 'editagency';
	$vars[] = 'editagent';
	return $vars;
}
add_filter( 'query_vars', 'add_custom_query_var' );

function resido_get_listing_cat( $post_id ) {
	$term_name = wp_get_object_terms( $post_id, 'listings_property', array( 'fields' => 'names' ) );
	if ( $term_name ) {
		return $term_name[0];
	} else {
		return null;
	}
}

if ( ! function_exists( 'resido_get_avat' ) ) {
	function resido_get_avat( $size = 70 ) {
		global $post;
		$wp_user_avatar = get_user_meta( $post->post_author, 'wp_user_avatar', true );
		if ( $wp_user_avatar ) {
			$avatar_url = wp_get_attachment_image_url( $wp_user_avatar, 'thumbnail' );
			echo '<img src="' . $avatar_url . '" class="author-avater-img" width="' . $size . '" height="' . $size . '"  alt="img">';
		} else {
			return get_avatar( $post->post_author, $size );
		}
	}
}


function resido_custom_excerpt_length( $length ) {
	return 5;
}
add_filter( 'excerpt_length', 'resido_custom_excerpt_length', 999 );

function resido_require_To_Var( $file ) {
	ob_start();
	require $file;
	return ob_get_clean();
}

function resido_get_ajax_nav_pagination( $paged, $max_num_pages ) {
	ob_start();
	?>
	<div class="pagination blogpagination_ajax">
		<?php
		echo paginate_links(
			array(
				'base'      => '%_%',
				'format'    => '?paged=%#%',
				'total'     => $max_num_pages,
				'current'   => max( 1, $paged ),
				'prev_text' => '<i class="fa fa-arrow-left"></i>',
				'next_text' => '<i class="fa fa-arrow-right"></i>',
			)
		);
		?>
		<span class="ajax_page_number"></span>
	</div>
	<?php
	return ob_get_clean();
}

function resido_get_country_list_by_location() {
	$rlisting_location = get_terms(
		array(
			'taxonomy'   => 'rlisting_location',
			'hide_empty' => false,
			'parent'     => 0,
		)
	);

	$country_list = array();
	if ( ! empty( $rlisting_location ) ) {
		foreach ( $rlisting_location as $single ) {
			$country_list[ $single->term_id ] = $single->name;
		}
	}

	return $country_list;
}

if ( ! function_exists( 'resido_get_country_tax_name' ) ) {
	function resido_get_country_tax_name() {
		$country = get_post_meta( get_the_ID(), 'rlisting_country', true );
		if ( $country ) {
			$country_obj = get_term( $country );
			if ( $country_obj ) {
				return strtoupper( $country_obj->slug );
			}
		}
	}
}

if ( ! function_exists( 'resido_get_city_tax_name' ) ) {

	function resido_get_city_tax_name() {
		$city = get_post_meta( get_the_ID(), 'rlisting_city', true );
		if ( $city ) {
			$city_obj = get_term( $city );
			if ( $city_obj ) {
				return $city_obj->name;
			}
		}
	}
}

function resido_get_city_and_country_tax() {
	return resido_get_city_tax_name() . ', ' . resido_get_country_tax_name();
}

function resido_get_country_list_city() {
	$rlisting_location = get_terms(
		array(
			'taxonomy'   => 'rlisting_location',
			'hide_empty' => false,
		)
	);

	$country_list = array();

	if ( ! empty( $rlisting_location ) ) {
		foreach ( $rlisting_location as $single ) {
			if ( $single->parent > 0 ) {
				$country_list[ $single->term_id ] = $single->name;
			}
		}
	}
	return $country_list;
}

function rlisting_subscr_check() {
	global $wpdb;
	$today_expire    = date( 'Y-m-d' );
	$strtotime       = strtotime( $today_expire );
	$check_date_data = get_transient( 'check_date' ); // Get transient val
	if ( $strtotime != $check_date_data ) {
		// Subscription Status Change
		$sposts      = $wpdb->get_col( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'rlisting_expire' AND  meta_value LIKE '%$today_expire%'" );
		$sposts      = implode( '\',\'', $sposts );
		$update_subs = $wpdb->get_col( "UPDATE {$wpdb->prefix}posts SET post_status = 'draft' WHERE id IN ('$sposts')" );
		// Listing Status Change
		$rposts      = $wpdb->get_col( "SELECT `post_id` FROM $wpdb->postmeta WHERE meta_key = 'user_package_id' AND  meta_value IN ('$sposts')" );
		$rposts      = implode( '\',\'', $rposts );
		$update_post = $wpdb->get_col( "UPDATE {$wpdb->prefix}posts SET post_status = 'draft' WHERE id IN ('$rposts')" );

		set_transient( 'check_date', $strtotime, 0 ); // Update transient val
	}
}
// add_action( 'init', 'rlisting_subscr_check' );

function resido_currency_symbol( $currency = '' ) {
	if ( empty( $currency ) ) {
		$currency = cl_admin_get_option( 'currency', 'USD' );
	}

	switch ( $currency ) :
		case 'GBP':
			$symbol = '&pound;';
			break;
		case 'BRL':
			$symbol = 'R&#36;';
			break;
		case 'EUR':
			$symbol = '&euro;';
			break;
		case 'USD':
		case 'AUD':
		case 'NZD':
		case 'CAD':
		case 'HKD':
		case 'MXN':
		case 'SGD':
			$symbol = '&#36;';
			break;
		case 'JPY':
			$symbol = '&yen;';
			break;
		case 'THB':
			$symbol = '&#3647;';
			break;
		case 'TRY':
			$symbol = '₺';
			break;
		case 'AOA':
			$symbol = 'Kz';
			break;
		case 'AED':
			$symbol = 'د.إ';
		case 'KES':
			$symbol = 'K';
			break;
		case 'NGN':
			$symbol = '₦';
			break;
		case 'GTQ':
			$symbol = 'Q';
			break;
	endswitch;
	return apply_filters( 'cl_currency_symbol', $symbol, $currency );

}
