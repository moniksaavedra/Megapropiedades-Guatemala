<?php
$provider        = WPERECCP()->front->listing_provider;
$rlisting_status = wp_get_object_terms( get_the_ID(), 'listing_status', array( 'fields' => 'names' ) );
$facebook        = 'http://www.facebook.com/sharer.php?u=' . get_the_permalink();
$twitter         = 'http://twitter.com/home?status=' . get_the_title() . '  ' . get_the_permalink();
$linked_in       = 'http://linkedin.com/shareArticle?mini=true&url=' . get_the_permalink() . '&title=' . get_the_title();
$telegram        = 'https://t.me/share/url?url=' . get_the_permalink() . '&text=' . get_the_title();
$vk              = 'http://vk.com/share.php?url=' . get_the_permalink();
?>
<div class="property_block_wrap style-4">
	<div class="prt-detail-title-desc">
		<?php
		if ( $rlisting_status ) {
			foreach ( $rlisting_status as $key => $single_rlisting_status ) {
				?>
				<span class="prt-types sale"><?php echo esc_html( $single_rlisting_status ); ?></span>
				<?php
			}
		}
		?>
		<h3 class="text-light"><?php the_title(); ?></h3>
		<span><i class="lni-map-marker"></i> <?php echo esc_html( get_post_meta( get_the_ID(), 'wperesds_address', true ) ); ?></span>
		<h3 class="prt-price-fix"><?php echo wp_kses( $provider->get_listing_pricing(), 'code_contxt' ); ?></h3>
		<div class="pbwts-social">
			<ul>
			<li><?php echo esc_html__( 'Share:', 'resido' ); ?></li>
					<li><a href="<?php echo esc_url( $facebook ); ?>" target="_blank"><i class="ti-facebook"></i></a><li>
					<li><a href="<?php echo esc_url( $twitter ); ?>" target="_blank"><i class="ti-twitter"></i></a><li>
					<li><a href="<?php echo esc_url( $linked_in ); ?>" target="_blank"><i class="ti-linkedin"></i></a><li>
					<li><a href="whatsapp://send?text=<?php echo get_the_permalink(); ?>" target="_blank"><i class="lni-whatsapp"></i></a><li>
					<li><a href="<?php echo esc_url( $telegram ); ?>" target="_blank"><i class="lni-telegram"></i></a><li>
					<li><a href="<?php echo esc_url( $vk ); ?>" target="_blank"><i class="lni-vk"></i></a><li>
			</ul>
		</div>
	</div>
</div>
