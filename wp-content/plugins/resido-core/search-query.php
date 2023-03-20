<?php
add_action( 'pre_get_posts', 'resido_advanced_search_query' );
function resido_advanced_search_query( $query ) {

	$rlisting_category = '';
	$rlisting_location = '';
	$rlisting_features = '';
	$rlisting_status   = '';
	$rlisting_bedrooms = '';
	$max_sale_or_rent  = '';
	$min_sale_or_rent  = '';
	$meta_arg          = '';
	$rl_bed            = '';

	if ( isset( $_REQUEST['search'] ) && $_REQUEST['search'] == 'advanced' ) {
		if ( $query->query_vars['post_type'] == 'cl_cpt' ) {
			$query->set( 'post_type', 'cl_cpt' );

			if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
				$query->set( 's', $_GET['s'] );
			}

			if ( isset( $_GET['listing_cate'] ) && ! empty( $_GET['listing_cate'] ) ) {
				$rlisting_category = array(
					'taxonomy' => 'listings_property',
					'terms'    => $_GET['listing_cate'],
					'field'    => 'slug',
				);
			}

			if ( isset( $_GET['listing_loc'] ) && ! empty( $_GET['listing_loc'] ) ) {
				$rlisting_location = array(
					'taxonomy' => 'listing_location',
					'terms'    => $_GET['listing_loc'],
					'field'    => 'slug',
				);
			}

			if ( isset( $_GET['rl_features'] ) && ! empty( $_GET['rl_features'] ) ) {
				$rlisting_features = array(
					'taxonomy' => 'listing_features',
					'terms'    => $_GET['rl_features'],
					'field'    => 'slug',
				);
			}

			if ( isset( $_GET['rlisting_st'] ) && ! empty( $_GET['rlisting_st'] ) ) {
				$rlisting_status = array(
					'taxonomy' => 'listing_status',
					'terms'    => $_GET['rlisting_st'],
					'field'    => 'slug',
				);
			}

			$meta_arg = array();

			$tax_query = array(
				'relation' => 'AND',
				$rlisting_category,
				$rlisting_features,
				$rlisting_location,
				$rlisting_status,
			);
			$query->set( 'tax_query', $tax_query );

			// Search by Meta
			if ( isset( $_GET['listing_minprice'] ) && ! empty( $_GET['listing_minprice'] ) ) {
				$min_sale_or_rent = array(
					'key'     => 'wperesds_pricing',
					'value'   => $_GET['listing_minprice'],
					'compare' => '>=',
					'type'    => 'numeric',
				);
			}
			if ( isset( $_GET['listing_maxprice'] ) && ! empty( $_GET['listing_maxprice'] ) ) {
				$max_sale_or_rent = array(
					'key'     => 'wperesds_pricing',
					'value'   => $_GET['listing_maxprice'],
					'compare' => '<=',
					'type'    => 'numeric',
				);
			}
			if ( isset( $_GET['listing_beds'] ) && ! empty( $_GET['listing_beds'] ) ) {

				$rlisting_bedrooms = array(
					'key'     => 'wperesds_beds',
					'value'   => $_GET['listing_beds'],
					'compare' => '==',
					'type'    => 'numeric',
				);
			}

			// For resido sidebar widget search
			if ( isset( $_GET['rl_bed'] ) && $_GET['rl_bed'] != '' ) {
				$rl_bed = array(
					'key'     => 'wperesds_beds',
					'value'   => $_GET['rl_bed'],
					'compare' => 'IN',
				);
			}
			// For resido sidebar widget search

			$meta_query = array(
				'relation' => 'AND',
				$rlisting_bedrooms,
				$min_sale_or_rent,
				$max_sale_or_rent,
				$rl_bed,
			);
			$query->set( 'meta_query', $meta_query );
			// Search by Meta

			if ( isset( $_GET['location'] ) && ! empty( $_GET['location'] ) ) {
				$meta_arg[] = array(
					'key'     => 'listing_location',
					'value'   => $_GET['location'],
					'compare' => 'LIKE',
				);
			}

			if ( isset( $_GET['city'] ) && ! empty( $_GET['city'] ) ) {
				$meta_arg[] = array(
					'key'     => 'rlisting_city',
					'value'   => $_GET['city'],
					'compare' => 'LIKE',
				);
			}

			if ( ! empty( $meta_arg ) ) {
				$meta_query = array(
					'relation' => 'AND',
					$meta_arg,
				);
				$query->set( 'meta_query', $meta_query );
			}
		}
	}
}
