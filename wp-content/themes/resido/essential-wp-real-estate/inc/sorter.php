<?php
global $pref;
$LISTING_Query  = WPERECCP()->front->query->get_listing_query();
$query_showing  = $LISTING_Query->post_count;
$query_total    = $LISTING_Query->found_posts;
$layout_options = WPERECCP()->front->listing_provider->get_gen_ted_link();
?>
<div class="row">
	<div class="col-lg-12">
		<div class="list-flex item-shorting-box">
			<div class="item-flex arch-post-count">
				<?php echo wp_sprintf( '<span>' . __( 'Showing', 'resido' ) . ' %s ' . __( 'of', 'resido' ) . ' %s ' . __( 'Results', 'resido' ) . '</span>', $query_showing, $query_total ); ?>
			</div>
			<div class="item-flex">
				<div class="sort-dropdown">
					<?php do_action( $pref . 'listing_sorter' ); ?>
				</div>
			</div>
			<ul class="item-flex shorting-list">
				<?php // do_action( $pref . 'listing_layout' ); ?>
				<?php
				use Essential\Restate\Common\Provider\Provider;
				$options        = array(
					'grid' => array(
						'name' => 'Grid',
						'type' => 'layout',
						'icon' => 'ti-layout-grid2',
					),
					'list' => array(
						'name' => 'List',
						'type' => 'layout',
						'icon' => 'ti-view-list',
					),
				);
				$layout_options = WPERECCP()->front->listing_provider->get_gen_ted_link( $options );

				foreach ( $layout_options as $layout_option ) {
					echo '<li class="list-inline-item"><a href="' . esc_url( $layout_option['link'] ) . '" class="sorter ' . esc_attr( $layout_option['active'] ) . ' ' . esc_attr( $layout_option['default'] ) . '"><i class="' . esc_attr( $layout_option['icon'] ) . '"></i></a></li>';
				}
				?>
			</ul>
		</div>
	</div>
</div>
