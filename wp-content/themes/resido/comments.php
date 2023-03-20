<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package resido
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */

if ( post_password_required() ) {
	return;
}
// You can start editing here -- including this comment!

?>

<div class="blog-details single-post-item format-standard">
	<div class="comment-area">
		<div class="all-comments">

			<?php
			if ( have_comments() ) :
				?>
				<h3 class="comments-title">
					<?php
					$resido_comment_count = get_comments_number();
					if ( '1' === $resido_comment_count ) {
						printf(
							/* translators: 1: title. */
							esc_html__( 'One Comment', 'resido' )
						);
					} else {
						printf( // WPCS: XSS OK.
							/* translators: 1: comment count number, 2: title. */
							esc_html( _nx( '%1$s Comment ', '%1$s Comments ', $resido_comment_count, 'comments title', 'resido' ), 'resido' ),
							number_format_i18n( $resido_comment_count )
						);
					}
					?>
				</h3>
				<?php the_comments_navigation(); ?>
				<div class="comment-list">
					<ul>
						<?php
						wp_list_comments(
							array(
								'style'       => 'ul',
								'callback'    => 'resido_comments',
								'avatar_size' => 80,
								'short_ping'  => true,
							)
						);
						?>
					</ul>
				</div>
				<?php
				the_comments_navigation();
				// If comments are closed and there are comments, let's leave a little note, shall we?
				if ( ! comments_open() ) :
					?>
					<p class="no-comments"><?php echo esc_html__( 'Comments are closed.', 'resido' ); ?></p>
					<?php
				endif;
			endif;
			?>
		</div>


		<div class="comment-box submit-form">
			<?php
			$user                 = wp_get_current_user();
			$resido_user_identity = $user->display_name;
			$req                  = get_option( 'require_name_email' );
			$aria_req             = $req ? " aria-required='true'" : '';
			$formargs             = array(
				'id_form'              => 'commentform',
				'id_submit'            => 'submit',
				'class_form'           => 'form-default',
				'title_reply'          => esc_html__( 'Post Comment', 'resido' ),
				'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'resido' ),
				'cancel_reply_link'    => esc_html__( 'Cancel Reply', 'resido' ),
				'label_submit'         => esc_html__( 'Submit Now', 'resido' ),
				'submit_button'        => '<button type="submit" name="%1$s" id="%2$s" class="%3$s btn btn-theme-light-2 rounded">%4$s</button>',
				'submit_field'         => '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12"><div class="cmt-form-submit form-group">%1$s %2$s</div></div></div>',
				'comment_field'        => '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12"><div class="form-group"><textarea placeholder="' . esc_attr__( 'Type your comments....', 'resido' ) . '" id="comment" class="form-control" cols="30" rows="6" name="comment" aria-required="true">' .
					'</textarea></div></div></div>',
				'must_log_in'          => '<div>' .
					sprintf(
						__( 'You must be <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#login">logged in</a> to post a comment.', 'resido' ), array( 'a' => array( 'href' => array() ) ),
						wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
					) . '</div>',
				'logged_in_as'         => '<div class="logged-in-as">' .
					sprintf(
						wp_kses( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="%4$s">Log out?</a>', 'resido' ), array( 'a' => array( 'href' => array() ) ) ),
						esc_url( admin_url( 'profile.php' ) ),
						$resido_user_identity,
						wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ),
						esc_attr__( 'Log out of this account', 'resido' )
					) . '</div>',
				'comment_notes_before' => '<p>' .
					esc_html__( 'Your email address will not be published.', 'resido' ) . ( $req ? '<span class="required">*</span>' : '' ) .
					'</p>',
				'comment_notes_after'  => '',
				'fields'               => apply_filters(
					'comment_form_default_fields',
					array(
						'author' =>
						'<div class="row"><div class="col-lg-6 col-md-6 col-sm-12"><div class="form-group">'
							. '<input id="author" class="form-control" name="author" placeholder="' . esc_attr__( 'Your name', 'resido' ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
							'" size="30"' . $aria_req . ' /></div></div>',
						'email'  =>
						'<div class="col-lg-6 col-md-6 col-sm-12"><div class="form-group">'
							. '<input id="email" class="form-control" name="email" type="text"  placeholder="' . esc_attr__( 'Your Email', 'resido' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
							'"' . $aria_req . ' /></div></div></div>',
					)
				),
			);
			comment_form( $formargs );
			?>
		</div>
	</div>
</div>
