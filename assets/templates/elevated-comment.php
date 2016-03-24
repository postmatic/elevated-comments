<?php
/**
 * Default elevated comment template.
 *
 * Available variables:
 *
 *  WP_Commment $comment
 */
?>
<blockquote class="postmatic-elevated">
  <h4><?php _e( 'From the Conversation', 'elevated-comments' ); ?></h4>
  <div class="postmatic-elevated-avatar"><?php echo get_avatar($comment->comment_author_email); ?></div>
  <div class="postmatic-elevated-comment"><?php echo $comment->comment_content ?></div>
  <cite>- <a href="<?php echo get_comment_link($comment); ?>"><?php echo $comment->comment_author; ?></a></cite>
</blockquote>
