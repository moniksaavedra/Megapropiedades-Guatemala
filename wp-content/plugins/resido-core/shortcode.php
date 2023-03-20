<?php
class Shortcode {


	/**
	 * Initializes the class
	 */
	function __construct() {
		add_shortcode( 'listing-home-search-form', array( $this, 'home_search_form' ) );
		add_shortcode( 'listing-resistration-form', array( $this, 'resido_resistration_form' ) );
		add_shortcode( 'listing-login-form', array( $this, 'get_login_form' ) );
		add_shortcode( 'listing-reset-form', array( $this, 'get_reset_password_form' ) );
	}

	/**
	 * Shortcode handler class
	 *
	 * @param  array  $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	public function home_search_form( $atts, $content = '' ) {
		$listing_slug         = cl_admin_get_option( 'listing_slug' ) ? cl_admin_get_option( 'listing_slug' ) : 'listings';
		$location_auto_search = isset( $listing_option['location_auto_search'] ) ? $listing_option['location_auto_search'] : 'yes';
		$location_id          = '';
		if ( $location_auto_search == 'yes' ) {
			$location_id = 'location';
		}

		$home_keyword_search  = isset( $listing_option['home_keyword_search'] ) ? $listing_option['home_keyword_search'] : 'yes';
		$home_location_search = isset( $listing_option['home_location_search'] ) ? $listing_option['home_location_search'] : 'yes';

		$location         = 'col-lg-3 col-md-3';
		$category         = 'col-lg-3 col-md-3';
		$button           = 'col-lg-2 col-md-2';
		$keyword_location = '';

		if ( $home_keyword_search == 'no' && $home_location_search == 'yes' ) {
			$location         = 'col-lg-5 col-md-5';
			$category         = 'col-lg-5 col-md-5';
			$keyword_location = 'keyword-location';
		} elseif ( $home_keyword_search == 'yes' && $home_location_search == 'no' ) {
			$keyword          = 'col-lg-5 col-md-5';
			$category         = 'col-lg-5 col-md-5';
			$keyword_location = 'keyword-location';
		} elseif ( $home_keyword_search == 'no' && $home_location_search == 'no' ) {
			$category         = 'col-lg-8 col-md-8';
			$button           = 'col-lg-4 col-md-4';
			$keyword_location = 'only_category';
		}
		if ( ! function_exists( 'rlisting_get_meta_list' ) ) {
			function rlisting_get_meta_list( $attr ) {
				$all_post_ids = get_posts( // Get all post of rlisting
					array(
						'fields'         => 'ids',
						'posts_per_page' => -1,
						'post_type'      => 'cl_cpt',
					)
				);
				$meta_val_arr = array(); // assign null array variable
				foreach ( $all_post_ids as $key => $post_id ) { // get every meta value from all post id
					$meta_price = get_post_meta( $post_id, $attr, true );
					array_push( $meta_val_arr, $meta_price );
				}
				$sort_meta_val_arr = array_unique( $meta_val_arr );
				sort( $sort_meta_val_arr ); // sort the sort_meta_val_arr value
				return $sort_meta_val_arr;
			}
		}

		if ( isset( $atts['style'] ) && $atts['style'] == 'style4' ) {
			?>
			<form method="get" id="advanced-searchform" role="search" action="<?php echo esc_url( home_url( $listing_slug ) ); ?>">
				<div class="pk-input-group">
					<input type="hidden" name="search" value="advanced">
					<input type="text" class="form-control" value="" placeholder="<?php echo esc_html__( 'Search for a Property', 'resido-core' ); ?>" name="s" id="name" />
					<input type="submit" class="btn btn-black" id="searchsubmit" value="<?php echo esc_html__( 'Go & Search', 'resido-core' ); ?>" />
				</div>
			</form>
			<?php
		} elseif ( isset( $atts['style'] ) && $atts['style'] == 'style3' ) {
			?>
			<form method="get" id="advanced-searchform" role="search" action="<?php echo esc_url( home_url( $listing_slug ) ); ?>">
				<div class="full-search-2 eclip-search italian-search hero-search-radius shadow">
					<div class="hero-search-content">
						<div class="row">
							<input type="hidden" name="search" value="advanced">
							<div class="col-lg-4 col-md-4 col-sm-12 b-r">
								<div class="form-group borders">
									<div class="input-with-icon">
										<input type="text" class="form-control" value="" placeholder="<?php echo esc_html__( 'Keywords...', 'resido-core' ); ?>" name="s" id="name" />
										<i class="ti-search"></i>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12">
								<div class="form-group borders">
									<div class="input-with-icon">
										<select data-placeholder="<?php echo esc_html__( 'Select Category', 'resido-core' ); ?>" name="listing_cate" class="form-control listing_cate">
											<option value=""><?php echo esc_html__( 'Select Category', 'resido-core' ); ?></option>
											<?php
											$rlisting_category = get_terms(
												array(
													'taxonomy'   => 'listings_property',
													'hide_empty' => false,
												)
											);
											if ( ! empty( $rlisting_category ) ) {
												foreach ( $rlisting_category as $single ) {
													echo '<option value="' . $single->slug . '">' . $single->name . '</option>';
												}
											}
											?>
										</select>
										<i class="ti-briefcase"></i>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12">
								<div class="form-group">
									<i class="ti-location-pin"></i>
									<div class="input-with-icon-style2">
										<select id="list_loc" name="listing_loc" class="form-control">
											<option value=""><?php echo esc_html__( 'All Cities', 'resido-core' ); ?></option>
											<?php
											$rlisting_location = get_terms(
												array(
													'taxonomy'   => 'listing_location',
													'hide_empty' => false,
												)
											);
											if ( ! empty( $rlisting_location ) ) {
												foreach ( $rlisting_location as $single ) {
													if ( $single->parent != 0 ) {
														$parent = get_term_by( 'id', $single->parent, 'listing_location' );
														echo '<option value="' . $single->slug . '">' . $single->name . ',' . $parent->name . '</option>';
													}
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-12">
								<div class="form-group">
									<input type="submit" class="btn search-btn search_sbmtfrm" id="searchsubmit" value="<?php esc_html_e( 'Search', 'resido-core' ); ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
			<?php
		} elseif ( isset( $atts['style'] ) && $atts['style'] == 'style2' ) {
			?>
			<!-- Style 2 -->
			<form method="get" id="advanced-searchform" role="search" action="<?php echo esc_url( home_url( $listing_slug . '/' ) ); ?>">
				<div class="full-search-2 eclip-search italian-search hero-search-radius shadow-hard mt-5">
					<div class="hero-search-content">
						<div class="row">
							<input type="hidden" name="search" value="advanced">
							<div class="col-lg-4 col-md-4 col-sm-12 b-r">
								<div class="form-group">
									<div class="choose-propert-type">
										<?php
										$rlisting_category = get_terms(
											array(
												'taxonomy' => 'listing_status',
												'hide_empty' => false,
											)
										);
										?>
										<ul>
											<?php
											if ( ! empty( $rlisting_category ) ) {
												foreach ( $rlisting_category as $key => $single ) {
													if ( $single->parent == 0 ) {
														if ( $key == 0 ) {
															$checked = 'checked';
														} else {
															$checked = null;
														}
														?>
														<li>
															<input value="<?php echo $single->slug; ?>" id="<?php echo $single->slug; ?>" class="checkbox-custom" name="rlisting_st" type="radio" <?php echo $checked; ?>>
															<label for="<?php echo $single->slug; ?>" class="checkbox-custom-label"><?php echo $single->name; ?></label>
														</li>
														<?php
													}
												}
											}
											?>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-5 col-sm-12 p-0 elio">
								<div class="form-group">
									<div class="input-with-icon">
										<input type="text" class="form-control" value="" placeholder="<?php echo esc_html__( 'Search for a Property', 'resido-core' ); ?>" name="s" id="name" />
										<img src="<?php echo RESIDO_IMG_URL . 'pin.svg'; ?>" width="20" alt="<?php esc_attr_e( 'resido_pin', 'resido-core' ); ?>">
									</div>
								</div>
							</div>
							<div class="col-lg-2 col-md-3 col-sm-12">
								<div class="form-group">
									<input type="submit" class="btn search-btn search_sbmtfrm" id="searchsubmit" value="<?php _e( 'Search', 'resido-core' ); ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
			<!-- Style 2 -->
			<?php
		} else {
			?>
			<!-- search Form -->
			<div class="hero-search-content side-form">
				<form method="get" id="advanced-searchform" role="search" action="<?php echo esc_url( home_url( $listing_slug . '/' ) ); ?>">
					<input type="hidden" name="search" value="advanced">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<div class="input-with-icon">
									<input type="text" class="form-control b-r" value="" placeholder="<?php _e( 'Keywords...', 'resido-core' ); ?>" name="s" id="name" />
									<img src="<?php echo RESIDO_IMG_URL . 'pin.svg'; ?>" width="18" alt="<?php esc_attr_e( 'resido_pin', 'resido-core' ); ?>" />
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'Min Price', 'resido-core' ); ?></label>
								<select id="minprice" name="listing_minprice" class="form-control">
									<option value="">&nbsp;</option>
									<?php
									$minp_val = rlisting_get_meta_list( 'wperesds_pricing' );
									foreach ( $minp_val as $key => $meta_val ) {
										echo '<option value="' . $meta_val . '">' . $meta_val . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'Max Price', 'resido-core' ); ?></label>
								<select id="maxprice" name="listing_maxprice" class="form-control">
									<option value="">&nbsp;</option>
									<?php
									$maxp_val = rlisting_get_meta_list( 'wperesds_pricing' );
									foreach ( $maxp_val as $key => $meta_val ) {
										echo '<option value="' . $meta_val . '">' . $meta_val . '</option>';
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php esc_html_e( 'Property Type', 'resido-core' ); ?></label>
								<select data-placeholder="<?php _e( 'Select Category', 'resido-core' ); ?>" name="listing_cate" class="form-control listing_cate fgdg">
									<option value=""><?php _e( 'Select Category', 'resido-core' ); ?></option>
									<?php
									$rlisting_category = get_terms(
										array(
											'taxonomy'   => 'listings_property',
											'hide_empty' => false,
										)
									);

									if ( ! empty( $rlisting_category ) ) {
										foreach ( $rlisting_category as $single ) {
											echo '<option value="' . $single->slug . '">' . $single->name . '</option>';
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label><?php echo esc_html__( 'Bed Rooms', 'resido-core' ); ?></label>
								<select id="bedrooms" name="listing_beds" class="form-control">
									<option value="">&nbsp;</option>
									<?php
									$bed_val = rlisting_get_meta_list( 'wperesds_beds' );
									foreach ( $bed_val as $key => $meta_val ) {
										echo '<option value="' . $meta_val . '">' . $meta_val . '</option>';
									}
									?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label><?php echo esc_html__( 'Property Location', 'resido-core' ); ?></label>
								<select id="list_loc" name="listing_loc" class="form-control">
									<option value=""><?php _e( 'All Cities', 'resido-core' ); ?></option>
									<?php
									$rlisting_location = get_terms(
										array(
											'taxonomy'   => 'listing_location',
											'hide_empty' => false,
										)
									);
									if ( ! empty( $rlisting_location ) ) {
										foreach ( $rlisting_location as $single ) {
											// if ( $single->parent != 0 ) {
												$parent = get_term_by( 'id', $single->parent, 'listing_location' );
												echo '<option value="' . $single->slug . '">' . $single->name . '</option>';
											// }
										}
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="hero-search-action">
						<input type="submit" class="btn search-btn search_sbmtfrm" id="searchsubmit" value="<?php _e( 'Search Result', 'resido-core' ); ?>" />
					</div>
				</form>
			</div>
			<!-- search Form -->
			<?php
		}
	}

	public function resido_resistration_form() {
		?>
		<div class="modal fade signup" id="signup" tabindex="-1" role="dialog" aria-labelledby="sign-up">
			<div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
				<div class="modal-content" id="sign-up">
					<span class="mod-close" data-bs-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></span>
					<div class="modal-body">
						<h4 class="modal-header-title"> <?php _e( 'Sign', 'resido-core' ); ?> <span class="theme-cl"><?php _e( 'Up', 'resido-core' ); ?></span></h4>
						<div class="login-form">
							<?php do_shortcode( '[cl_register_user]' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function get_reset_password_form() {
		?>
		<div class="modal fade" id="reset" tabindex="-1" role="dialog" aria-labelledby="resetrmodal">
			<div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
				<div class="modal-content" id="resetrmodal">
					<span class="mod-close" data-bs-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></span>
					<div class="modal-body">
						<h4 class="center"><?php _e( 'Reset YOUR PASSWORD', 'resido-core' ); ?></h4>
						<div class="login-form">
							<p class="status"></p>
							<form class="ajax-auth" id="forgot_password" action="forgot_password" method="post">
								<label><?php _e( 'Username or E-mail:', 'resido-core' ); ?><br> </label>
								<div class="form-group">
									<div class="input-with-icon">
										<input name="user_login" id="user_login" class="form-control" type="text" />
									</div>
								</div>
								<?php wp_nonce_field( 'ajax-forgot-nonce', 'forgotsecurity' ); ?>
								<div class="form-group">
									<input type="submit" class="btn btn-md full-width pop-login submit_button" value="<?php _e( 'Get New Password', 'resido-core' ); ?>" tabindex="100">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function get_login_form() {
		?>
		<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="registermodal">
			<div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
				<div class="modal-content" id="registermodal">
					<span class="mod-close" data-bs-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></span>
					<div class="modal-body">
						<h4 class="modal-header-title"><?php _e( 'Log', 'resido-core' ); ?> <span class="theme-cl"><?php _e( 'In', 'resido-core' ); ?></span></h4>
						<?php do_shortcode( '[cl_admin_login]' ); ?>
						<div class="text-center">
							<p class="mt-1">
								<a href="JavaScript:Void(0);" id="forgot_pass" class="link" data-bs-toggle="modal" data-bs-target="#reset"><?php _e( 'Forgot password?', 'resido-core' ); ?></a>
							</p>
						</div>
						<div class="text-center">
							<?php _e( 'Don\'t have an account', 'resido-core' ); ?>
							<a href="JavaScript:Void(0);" id="login_to_resistration" class="link" data-bs-toggle="modal" data-bs-target="#signup">
								<?php _e( 'Registration', 'resido-core' ); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}


}
new Shortcode();
