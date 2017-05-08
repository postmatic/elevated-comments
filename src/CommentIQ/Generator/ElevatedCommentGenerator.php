<?php

/*
 * This file is part of the Elevated Comments plugin.
 *
 * (c) Carl Alexander <contact@carlalexander.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * HTML generator for elevated comments.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
class CommentIQ_Generator_ElevatedCommentGenerator
{
    /**
     * The post meta key used to store the elevated comment ID.
     *
     * @var string
     */
    private $elevated_comment_id_meta_key;

    /**
     * The post types that we want to insert elevated comments into.
     *
     * @var array
     */
    private $post_types;

    /**
     * Path to the default template used by the elevated comment generator.
     *
     * @var string
     */
    private $default_template_path;

    /**
     * Constructor.
     *
     * @param string $default_template_path
     * @param array  $post_types
     */
    public function __construct($default_template_path, array $post_types = array())
    {
        $this->elevated_comment_id_meta_key = 'commentiq_elevated_comment_id';
        $this->post_types = $post_types;
        $this->default_template_path = $default_template_path;
    }

    /**
     * Generate the elevated comment HTML for the given post.
     *
     * @param WP_Post $post
     *
     * @return string
     */
    public function generate(WP_Post $post)
    {
        if (!in_array($post->post_type, $this->post_types)) {
            return '';
        }

        $elevated_comment_id = get_post_meta($post->ID, 'commentiq_elevated_comment_id', true);

        if (!is_numeric($elevated_comment_id)) {
            return '';
        }

        $elevated_comment = get_comment($elevated_comment_id);
        
		/**
		 * Filter: elevated_allow_post_author
		 *
		 * Whether to allow post author comments to be elevated.
		 *
		 * @since 1.1.6
		 *
		 * @param bool false if not, true if yes
		 */
        $allow_post_author  = apply_filters( 'elevated_allow_post_author', false );

        if ( ! $elevated_comment instanceof WP_Comment || ! $this->is_valid_comment($elevated_comment ) || ( ( $post->post_author === $elevated_comment->user_id ) && ! $allow_post_author ) ) {
            return '';
        }

        return $this->render_elevated_comment($elevated_comment);
    }

    /**
     * Get the PHP template that the elevated comment generator will use.
     *
     * @return string
     */
    private function get_template()
    {
        $template = get_query_template('commentiq-elevated-comment');

        if (empty($template)) {
            $template = $this->default_template_path;
        }

        return $template;
    }

    /**
     * Checks if the given comment is valid.
     *
     * @param WP_Comment $comment
     *
     * @return bool
     */
    private function is_valid_comment(WP_Comment $comment)
    {
        return '1' == $comment->comment_approved && empty($comment->comment_type);
    }

    /**
     * Renders the elevated comment.
     *
     * The method does this using out buffering. It'll buffer the output of
     * the elevated comment template that gets included. It'll then return
     * its output once done processing.
     *
     * @param WP_Comment $comment
     *
     * @return string
     */
    private function render_elevated_comment(WP_Comment $comment)
    {
        $comment_show_in_content = (bool)CommentIQ_Admin_Settings::get_plugin_option( 'show_in_content' );
        
        /**
		 * Filter: elevated_show_in_content
		 *
		 * Whether to show the elevated comment.
		 *
		 * @since 1.1.1
         *
		 * @param bool  $comment_show_in_content true to show in content, false if not.
		 * @param int   Comment ID
		 * @param int   Comment Post ID
		 */
        $comment_show_in_content = (bool)apply_filters( 'elevated_show_in_content', $comment_show_in_content, $comment->comment_ID, $comment->comment_post_ID );
        
        if ( false === $comment_show_in_content ) {
            return;
        }
                
        ob_start();

        include $this->get_template();

        return ob_get_clean();
    }
}
