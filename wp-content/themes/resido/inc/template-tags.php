<?php
if ( ! function_exists( 'resido_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function resido_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) : ?>
			<div class="post-featured-img">
					<?php
					the_post_thumbnail(
						'full',
						array(
							'class' => 'img-fluid',
							'alt'   => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
					?>
			</div>
			<?php else : ?>
			<a href="<?php esc_url( the_permalink() ); ?>">
				<?php
				the_post_thumbnail(
					'full',
					array(
						'class' => 'img-fluid',
						'alt'   => the_title_attribute(
							array(
								'echo' => false,
							)
						),
					)
				);
				?>
			</a>
				<?php
		endif; // End is_singular().
	}
endif;


if ( ! function_exists( 'resido_post_grid_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function resido_post_grid_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>
			<div class="post-featured-img">
			<?php
			the_post_thumbnail(
				'post-grid-thumbnail',
				array(
					'class' => 'img-fluid',
					'alt'   => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
			?>
			</div>
			<?php else : ?>
			<a href="<?php the_permalink(); ?>">
				<?php
				the_post_thumbnail(
					'post-grid-thumbnail',
					array(
						'class' => 'img-fluid',
						'alt'   => the_title_attribute(
							array(
								'echo' => false,
							)
						),
					)
				);
				?>
			</a>
				<?php
		endif; // End is_singular().
	}
endif;


if ( ! function_exists( 'resido_post_tags' ) ) :

	/*
	 Displays an optional post thumbnail.

	 Wraps the post thumbnail in an anchor element on index views, or a div
	 element when on single views.
	 */
	function resido_post_tags( $postid ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '<h4 class="pbm-title">' . esc_html__( 'Tags:', 'resido' ) . '</h4><ul class="list"><li>', '</li><li>', '</li></ul>' );
		if ( $tags_list ) {
			printf( '<div class="post-tags">' . esc_html( '%1$s' ) . '</div>', $tags_list ); // WPCS: XSS OK.
		}
	}

endif;

if ( ! function_exists( 'resido_posted_on' ) ) :

	/*
	 Prints HTML with meta information for the current post-date/time.
	 */
	function resido_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html( '%s' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		printf( $posted_on ); // WPCS: XSS OK.
	}

endif;

if ( ! function_exists( 'resido_post_category' ) ) :

	/*
	 Prints HTML with meta information for the categories, tags and comments.
	 */
	function resido_post_category() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'resido' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<li><i class="fa fa-folder-open"></i>' . esc_html( '%1$s' ) . '</li>', $categories_list ); // WPCS: XSS OK.
			}
		}
	}

endif;

if ( ! function_exists( 'resido_posted_by' ) ) :

	/*
	 Prints HTML with meta information for the current author.
	 */
	function resido_posted_by() {
		global $post;
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( '%s', 'post author', 'resido' ),
			'<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID', $post->post_author ) ) ) . '"><span class="icons"><i class="ti-user"></i></span>by ' . esc_html( ucfirst( get_the_author_meta( 'display_name', $post->post_author ) ) ) . '</a>'
		);
		printf( $byline ); // WPCS: XSS OK.
	}

endif;

if ( ! function_exists( 'resido_comments_count' ) ) :

	function resido_comments_count() {
		if ( get_comments_number( get_the_ID() ) == 0 ) {
			$comments_count = '<a href="' . esc_url( get_permalink() ) . '"><span class="icons"><i class="ti-comment-alt"></i></span>' . get_comments_number( get_the_ID() ) . ' Comments' . '</a>';
		} elseif ( get_comments_number( get_the_ID() ) > 1 ) {
			$comments_count = '<a href="' . esc_url( get_permalink() ) . '#respond"><span class="icons"><i class="ti-comment-alt"></i></span>' . get_comments_number( get_the_ID() ) . ' Comments' . '</a>';
		} else {
			$comments_count = '<a href="' . esc_url( get_permalink() ) . '#respond"><span class="icons"><i class="ti-comment-alt"></i></span>' . get_comments_number( get_the_ID() ) . ' Comment' . '</a>';
		}
		echo sprintf( esc_html( '%s' ), $comments_count ); // WPCS: XSS OK.
	}

endif;

if ( ! function_exists( 'resido_kses_allowed_html' ) ) :
	function resido_kses_allowed_html( $resido_tags, $resido_context ) {
		switch ( $resido_context ) {
			case 'code_contxt':
				$resido_tags = array(
					'a'      => array(
						 'href' => array(),
						 'target' => array(),
						 'nofollow' => array() 
						),
					'i'      => array(
						'class' => array(),
					),
					'p'      => array(),
					'ul'     => array(
						'class' => array(),
					),
					'li'     => array(),
					'sub'     => array(),
					'img'    => array(
						'class'  => array(),
						'height' => array(),
						'width'  => array(),
						'src'    => array(),
						'alt'    => array(),
					),
					'em'     => array(),
					'br'     => array(),
					'span'   => array(
						'class' => array(),
					), 
					'strong' => array(),
				);
				return $resido_tags;
			case 'resido_img':
				$resido_tags = array(
					'img' => array(
						'class'  => array(),
						'height' => array(),
						'width'  => array(),
						'src'    => array(),
						'alt'    => array(),
					),
				);
				return $resido_tags;
			default:
				return $resido_tags;
		}
	}

	add_filter( 'wp_kses_allowed_html', 'resido_kses_allowed_html', 10, 2 );
endif;

function resido_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	if ( is_singular() ) {
		$classes[] = 'blog-page';
	}
	$theme_initialized       = resido_get_options( 'theme_initialized' );
	$theme_initialized_class = 'base-theme';
	if ( $theme_initialized == 1 ) :
		$theme_initialized_class = '';
	endif;

	$classes[] = $theme_initialized_class;

	$resido_theme_metabox_box_layout = get_post_meta( get_the_ID(), 'resido_theme_metabox_box_layout', true );
	$theme_box_mode                  = resido_get_options( 'theme_box_mode' );
	$theme_box_mode_class            = '';
	if ( $theme_box_mode == 1 || $resido_theme_metabox_box_layout == 'on' ) :
		$theme_initialized_class = 'main_page active_boxlayout bg';
	endif;

	$classes[] = $theme_initialized_class;

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'resido_body_classes' );


if ( ! function_exists( 'resido_comments' ) ) {

	function resido_comments( $comment, $args, $depth ) {
		extract( $args, EXTR_SKIP );
		$args['reply_text'] = esc_html__( '« Reply', 'resido' );

		$comment_class_ping = 'yes-ping';
		if ( $comment->comment_type != 'trackback' && $comment->comment_type != 'pingback' ) :
			$comment_class_ping = '';
		endif;
		?>

		<li class='single-comment comment <?php echo esc_attr( $comment_class_ping ); ?>' id='comment-<?php comment_ID(); ?>'>
			<article>
				<div class='comment-author'>
					<?php
					$resido_avatar = get_avatar( $comment, 100, null, null, array( 'class' => array() ) );
					if ( $resido_avatar ) {
						?>
						<?php
						print get_avatar( $comment, 100, null, null, array( 'class' => array() ) );
						?>
						<?php
					}
					?>
				</div>
				<div class='comment-details'>
					<div class='comment-meta'>
						<div class='comment-left-meta'>
							<h4 class='author-name'>
								<?php echo get_comment_author_link(); ?>
							</h4>
							<div class='comment-date'>
								<?php
								comment_time( get_option( 'date_format' ) );
								?>
							</div>
						</div>
						<div class='comment-reply'>

							<?php
							$replyBtn = 'reply';
							echo preg_replace(
								'/comment-reply-link/',
								'comment-reply-link ' . $replyBtn,
								get_comment_reply_link(
									array_merge(
										$args,
										array(
											'reply_text' => "<span class='icona'><i class='ti-back-left'></i></span>" . esc_html__( ' Reply ', 'resido' ),
											'depth'      => $depth,
											'max_depth'  => $args['max_depth'],
										)
									)
								),
								1
							);
							?>
						</div>
					</div>
					<div class='comment-text'>
						<?php
						comment_text();
						?>
					</div>
				</div>
			</article>
		<?php
	}
}
