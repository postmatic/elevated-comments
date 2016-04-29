<?php
/**
 * Default elevated comment template.
 *
 * Available variables:
 *
 *  WP_Commment $comment
 */
$comment_author = $comment->comment_author;
if ( 0 !== $comment->user_id ) {
    $user = get_user_by( 'id', $comment->user_id );
    if ( is_a( $user, 'WP_User' ) ) {
        $user_nicename = get_user_meta( $comment->user_id, 'nickname', true );
        if ( $comment_author !== $user_nicename ) {
            $comment_author = $user_nicename;
        } else {
            $comment_author = $user->data->display_name;
        }
        
    }
}
?>
<div class="postmatic-elevated">

  <div class="postmatic-elevated-comment"><?php echo apply_filters('comment_text', $comment->comment_content); ?></div>
  <div class="postmatic-elevated-avatar"><?php echo get_avatar($comment->comment_author_email); ?></div>
  <div class="postmatic-elevated-author"><?php echo esc_html( $comment_author ); ?></div>
  <a class="postmatic-elevated-link" href="<?php echo esc_url( get_comment_link($comment) ); ?>"><?php esc_html_e( 'From the comments', 'elevated-comments' ); ?></a>
  
</div>