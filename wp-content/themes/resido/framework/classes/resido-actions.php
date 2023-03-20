<?php
class Resido_Int {

	/**
	 * preloader compatibility.
	 */
	public static function resido_preloader() {
		$preloader_on_off = resido_get_options( 'preloader' );
		if ( $preloader_on_off ) : ?>
			<!-- preloader -->
			<div id="preloader">
				<div class="preloader"><span></span><span></span></div>
			</div>
			<!-- preloader -->
			<?php
		endif;
	}

	/**
	 * All header and breadcrumb.
	 */
	public static function resido_breadcrumb() {
		$breadcrumb_title = 'Resido';
		$breadcrumb_class = 'blog-breadcrumb';
		$breadcrumb_subtitle = '';

		// deafult blog
		if ( is_front_page() && is_home() ) :
			$breadcrumb_title    = 'Home';
			$breadcrumb_class    = 'deafult-home-breadcrumb';
			$breadcrumb_subtitle = '';
			
			// custom home or deafult
		elseif ( is_front_page() && ! is_home() ) :
			$breadcrumb_title    = 'Homepage';
			$breadcrumb_class    = 'custom-home-breadcrumb';
			$breadcrumb_subtitle = '';
			// blog homepage
		elseif ( is_home() ) :
			$page_title = resido_get_options( 'blog_page_title' );
			if ( $page_title == '' || ! isset( $page_title ) ) {
				$page_title = 'Blog';
			}
			$breadcrumb_title = $page_title;
			$breadcrumb_class = 'blog-breadcrumb';

			// post type rlisting archive page
		elseif ( is_archive( 'rlisting' ) && get_post_type() == 'rlisting' ) :
			$breadcrumb_title = get_the_archive_title();
			$breadcrumb_class = 'rlisting-none-breadcrumb';
		elseif ( is_archive( 'cl_cpt' ) && get_post_type() == 'cl_cpt' ) :
			$breadcrumb_title    = resido_get_options( 'archive_page_title' );
			$breadcrumb_subtitle = resido_get_options( 'archive_page_subtitle' );
			$ar_top_breadcrumb   = resido_get_options( 'ar_top_breadcrumb' );
			if ( $ar_top_breadcrumb == '1' ) {
				$breadcrumb_class = '';
			} else {
				$breadcrumb_class = 'rlisting-none-breadcrumb';
			}
			
		elseif ( is_archive( 'listing_agencies' ) && get_post_type() == 'listing_agencies' ) :
			$agency_breadcrumb = resido_get_options( 'agency_breadcrumb' );
			if ( $agency_breadcrumb == '1' ) {
				$breadcrumb_class = '';
			} else {
				$breadcrumb_class = 'ragencies-none-breadcrumb';
			}
			$breadcrumb_class    = '';
			$breadcrumb_title    = resido_get_options( 'agency_archive_page_title' );
			$breadcrumb_subtitle = resido_get_options( 'agency_archive_page_subtitle' );
		elseif ( is_archive( 'listing_agents' ) && get_post_type() == 'listing_agents' ) :
			$agent_breadcrumb = resido_get_options( 'agent_breadcrumb' );
			if ( $agent_breadcrumb == '1' ) {
				$breadcrumb_class = '';
			} else {
				$breadcrumb_class = 'ragencies-none-breadcrumb';
			}
			$breadcrumb_title    = resido_get_options( 'agent_archive_page_title' );
			$breadcrumb_subtitle = resido_get_options( 'agent_archive_page_subtitle' );
			// post type ragencies archive page
		elseif ( is_category() ) :
			$breadcrumb_title    = get_the_archive_title();
			$breadcrumb_class    = 'category-breadcrumb';
			$breadcrumb_subtitle = resido_get_options( 'breadcrumb_subtitle' );

			// post type ragencies archive page
		elseif ( is_archive() && is_archive( 'post' ) ) :
			$breadcrumb_title    = get_the_archive_title();
			$breadcrumb_class    = 'category-breadcrumb';
			$breadcrumb_subtitle = resido_get_options( 'breadcrumb_subtitle' );
			// post type ragencies archive page
		elseif ( ( is_archive() && is_archive( 'ragencies' ) ) ) :
			$breadcrumb_title = get_the_archive_title();
			$breadcrumb_class = 'ragencies-none-breadcrumb';

			// post type ragents archive page
		elseif ( is_archive() && is_archive( 'ragents' ) ) :
			$breadcrumb_title = get_the_archive_title();
			$breadcrumb_class = 'ragencies-none-breadcrumb';

			// post type ragencies single page
		elseif ( is_single() && is_singular( 'ragencies' ) ) :
			$breadcrumb_title = get_the_archive_title();
			$breadcrumb_class = 'ragencies-none-breadcrumb';

			// post type ragents single page
		elseif ( is_single() && is_singular( 'ragents' ) ) :
			$breadcrumb_title = get_the_archive_title();
			$breadcrumb_class = 'ragencies-none-breadcrumb';

		elseif ( is_single() && is_singular( 'listing_agencies' ) ) :
			$breadcrumb_title    = get_the_archive_title();
			$breadcrumb_class    = 'ragencies-none-breadcrumb';
			$breadcrumb_subtitle = '';
		elseif ( is_single() && is_singular( 'listing_agents' ) ) :
			$breadcrumb_title    = get_the_archive_title();
			$breadcrumb_class    = 'ragencies-none-breadcrumb';
			$breadcrumb_subtitle = '';
			// archive page
		elseif ( is_archive() ) :
			$breadcrumb_title = get_the_archive_title();
			$breadcrumb_class = 'archive-breadcrumb';

			// post type single
		elseif ( is_single() && is_singular( 'rlisting' ) || is_singular( 'cl_cpt' ) ) :
			$breadcrumb_title    = get_the_title();
			$breadcrumb_class    = 'rlisting-single-breadcrumb';
			$breadcrumb_subtitle = '';
			// post type single
		elseif ( is_single() && is_singular( 'service' ) ) :
			$breadcrumb_title = get_the_title();
			$breadcrumb_class = 'post-single-breadcrumb';

			// post type single
		elseif ( is_single() && is_singular( 'post' ) ) :
			$blog_title = resido_get_options( 'blog_page_single_title' );
			if ( $blog_title == '' || ! isset( $blog_title ) ) {
				$blog_title = 'Blog Details';
			}
			$breadcrumb_title    = $blog_title;
			$breadcrumb_class    = 'post-single-breadcrumb';
			$breadcrumb_subtitle = resido_get_options( 'breadcrumb_subtitle' );
			// 404 Page
		elseif ( is_404() ) :
			$breadcrumb_title    = esc_html__( 'Error Page', 'resido' );
			$breadcrumb_class    = 'error-breadcrumb';
			$breadcrumb_subtitle = '';
			// Search Page
		elseif ( is_search() ) :
			if ( have_posts() ) :
				$breadcrumb_title = esc_html__( 'Search Results for: ', 'resido' ) . get_search_query();
			else :
				$breadcrumb_title = esc_html__( 'Nothing Found', 'resido' );
			endif;
			$breadcrumb_class    = 'search-breadcrumb';
			$breadcrumb_subtitle = resido_get_options( 'breadcrumb_subtitle' );
			// Single Page
		elseif ( ! is_home() && ! is_front_page() && ! is_search() && ! is_404() && ! is_single() ) :
			$breadcrumb_title    = get_the_title();
			$breadcrumb_class    = 'page-breadcrumb';
			$breadcrumb_subtitle = '';
		endif;
		$breadcrumb_meta_subtitle = get_post_meta( get_the_ID(), 'resido_core_breadcrumb_subtitle', true );
		$en_dis_breadcrumb        = get_post_meta( get_the_ID(), 'resido_core_en_dis_breadcrumb', true );
		$breadcrumb_status        = resido_get_options( 'breadcrumb_status' );
		if ( $breadcrumb_status ) {
			$breadcrumb_status = $breadcrumb_status;
		} else {
			if ( is_home() || is_single() ) {
				$breadcrumb_status = 0;
			} else {
				$breadcrumb_status = 1;
			}
		}
		$breadcrumb_list = resido_get_options( 'breadcrumb_list' );
		if ( isset( $breadcrumb_meta_subtitle ) && $breadcrumb_meta_subtitle != '' ) {
			$breadcrumb_subtitle = get_post_meta( get_the_ID(), 'resido_core_breadcrumb_subtitle', true );
		} elseif ( $breadcrumb_subtitle ) {
			$breadcrumb_subtitle = $breadcrumb_subtitle;
		}
		
		if ( $en_dis_breadcrumb != 2 && $breadcrumb_status == 1 && $breadcrumb_class != 'rlisting-single-breadcrumb' && $breadcrumb_class != 'rlisting-none-breadcrumb' && $breadcrumb_class != 'ragencies-none-breadcrumb' ) {
			if ( ! is_post_type_archive( 'ragencies' ) && ! is_post_type_archive( 'ragents' ) ) {
				?>
			<div class="page-title <?php echo esc_attr( $breadcrumb_class ); ?>">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<h1 class="ipt-title"><?php echo sprintf( __( '%s', 'resido' ), $breadcrumb_title ); ?></h2>
							<?php if ( $breadcrumb_subtitle ) { ?>
								<span class="ipn-subtitle"><?php echo esc_html( $breadcrumb_subtitle ); ?></span>
							<?php } ?>
							<?php if ( function_exists( 'bcn_display' ) && $breadcrumb_list != 0 ) : ?>
								<ul class="bread-crumb">
									<?php bcn_display(); ?>
								</ul>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
					<?php
			}
		}
	}



	/**
	 * top bar search compatibility.
	 */
	public static function resido_search() {
		?>
		<div class="search-box-outer">
			<div class="dropdown">
				<button class="search-box-btn" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flaticon-magnifying-glass"></i></button>
				<div class="dropdown-menu search-panel" aria-labelledby="dropdownMenu3">
					<div class="form-container">
						<form method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<div class="form-group">
								<input type="search" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_attr_e( 'Search...', 'resido' ); ?>" required="">
								<button type="submit" class="search-btn"><span class="fas fa-search"></span></button>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * autor box compatibility.
	 */
	public static function resido_authore_box() {
		global $post;

		$auth_social   = get_the_author_meta( 'auth_social', $post->post_author );
		$auth_facebook = get_the_author_meta( 'facebook', $post->post_author );
		$auth_twitter  = get_the_author_meta( 'twitter', $post->post_author );
		$auth_behance  = get_the_author_meta( 'behance', $post->post_author );
		$auth_youtube  = get_the_author_meta( 'youtube', $post->post_author );
		$auth_linkedin = get_the_author_meta( 'linkedin', $post->post_author );
		$auth_phone    = get_the_author_meta( 'phone', $post->post_author );

		?>
		<div class="blog-details single-post-item format-standard">
			<div class="posts-author">
				<span class="img"><?php echo wp_kses( get_avatar( $post->post_author, 170 ), 'resido_img' ); ?></span>
				<h3 class="pa-name"><?php echo esc_html( ucfirst( get_the_author_meta( 'display_name', $post->post_author ) ) ); ?></h3>
			<?php if ( isset( $auth_social ) ) { ?>
					<ul class="social-links">
						<?php if ( $auth_facebook ) { ?>
							<li><a href="<?php echo esc_url( $auth_facebook ); ?>"><i class="fab fa-facebook-f"></i></a></li>
							<?php
						}
						if ( $auth_twitter ) {
							?>
							<li><a href="<?php echo esc_url( $auth_twitter ); ?>"><i class="fab fa-twitter"></i></a></li>
							<?php
						}
						if ( $auth_behance ) {
							?>
							<li><a href="<?php echo esc_url( $auth_behance ); ?>"><i class="fab fa-behance"></i></a></li>
							<?php
						}
						if ( $auth_youtube ) {
							?>
							<li><a href="<?php echo esc_url( $auth_youtube ); ?>"><i class="fab fa-youtube"></i></a></li>
							<?php
						}
						if ( $auth_linkedin ) {
							?>
							<li><a href="<?php echo esc_url( $auth_linkedin ); ?>"><i class="fab fa-linkedin-in"></i></a></li>
							<?php
						}
						?>

					</ul>
					<?php
			}
			?>
				<p class="pa-text"><?php echo wp_kses_post( get_the_author_meta( 'user_description', $post->post_author ) ); ?></p>
			</div>
		</div>
			<?php
	}

}
$resido_int = new resido_Int();
