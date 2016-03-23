<?php
/**
 * Default elevated comment template.
 *
 * Available variables:
 *
 *  WP_Commment $comment
 */
?>
<blockquote>
  <div><?php echo get_avatar($comment->comment_author_email); ?></div>
  <div><?php echo $comment->comment_content ?></div>
  <div>- <a href="<?php echo get_comment_link($comment); ?>"><?php echo $comment->comment_author; ?></a></div>
</blockquote>
