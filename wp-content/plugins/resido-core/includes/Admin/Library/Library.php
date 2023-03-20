<?php
namespace Resido\Helper\Admin\Library;

class Library {

	public static function ElementorLibrary() {
		$detoxPageslist   = get_posts(
			array(
				'post_type'      => 'elementor_library',
				'posts_per_page' => -1,
			)
		);
		$detoxPagesArrayt = array();
		if ( ! empty( $detoxPageslist ) ) {
			foreach ( $detoxPageslist as $page ) {
				$detoxPagesArrayt[ $page->ID ] = $page->post_title;
			}
		}
		return $detoxPagesArrayt;
	}

	public static function getAllPages() {
		$args    = array(
			'post_type'      => 'page',
			'posts_per_page' => -1,
		);
		$catlist = array();
		if ( $categories = get_posts( $args ) ) {
			foreach ( $categories as $category ) {
				(int) $catlist[ $category->ID ] = $category->post_title;
			}
		} else {
			(int) $catlist['0'] = esc_html__( 'No Pages Found!', 'resido-core' );
		}
		return $catlist;
	}
}
