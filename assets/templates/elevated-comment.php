<?php
/**
 * Default elevated comment template.
 *
 * Available variables:
 *
 *  WP_Commment $comment
 */
?>
<div class="postmatic-elevated">

  <div class="postmatic-elevated-comment"><p><?php echo $comment->comment_content ?></p></div>
  <div class="postmatic-elevated-avatar"><?php echo get_avatar($comment->comment_author_email); ?></div>
  <div class="postmatic-elevated-author"><?php echo $comment->comment_author; ?></div>
  <a class="postmatic-elevated-link" href="<?php echo get_comment_link($comment); ?>"><?php _e( 'From the comments', 'elevated-comments' ); ?></a>
  
</div>