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
 * Subscriber that sends posts and comments to the Comment IQ API.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
class CommentIQ_Subscriber_CommentIQAPISubscriber implements CommentIQ_EventManagement_SubscriberInterface
{
    /**
     * The Comment IQ API client.
     *
     * @var CommentIQ_API_Client
     */
    private $api_client;

    /**
     * The post meta key used to store the article ID.
     *
     * @var string
     */
    private $article_id_meta_key;

    /**
     * The post meta key used to store the comment ID returned by
     * the Comment IQ API.
     *
     * @var string
     */
    private $comment_id_meta_key;

    /**
     * The post meta key used to store the comment details returned by
     * the Comment IQ API.
     *
     * @var string
     */
    private $comment_details_meta_key;

    /**
     * The post meta key used to store the elevated comment ID.
     *
     * @var string
     */
    private $elevated_comment_id_meta_key;

    /**
     * The post types that are tracked by the Comment IQ API.
     *
     * @var array
     */
    private $post_types;

    /**
     * Constructor.
     *
     * @param CommentIQ_API_Client $api_client
     * @param array                $post_types
     */
    public function __construct(CommentIQ_API_Client $api_client, array $post_types = array())
    {
        $this->api_client = $api_client;
        $this->article_id_meta_key = 'commentiq_article_id';
        $this->comment_id_meta_key = 'commentiq_comment_id';
        $this->comment_details_meta_key = 'commentiq_comment_details';
        $this->elevated_comment_id_meta_key = 'commentiq_elevated_comment_id';
        $this->post_types = $post_types;
    }

    /**
     * {@inheritdoc}
     */
    public static function get_subscribed_events()
    {
        return array(
            'wp_insert_comment' => 'on_comment_new',
            'edit_comment' => 'on_comment_edit',
            'save_post' => array('on_post_save', 10, 2),
        );
    }
    
    /**
     * Retries getting comment information. Assumes comment meta empty.
     *
     * @param  int   $comment_id
     * @return bool  true if successful, false if not
     */
    public function retry_comment( $comment_id ) {

        // Try to add new comment
        $result = $this->on_comment_new( $comment_id );
        return $result;
    }

    /**
     * Updates an existing comment with the Comment IQ API.
     *
     * @param int $comment_id
     */
    public function on_comment_edit($comment_id)
    {
        $comment = get_comment($comment_id);

        if (!$comment instanceof WP_Comment || !$this->is_valid_comment($comment)) {
            return;
        }

        $commentiq_comment_id = get_comment_meta($comment_id, $this->comment_id_meta_key, true);

        if ( ! is_numeric( $commentiq_comment_id ) ) {

            // Retry getting comment on edit
            $retry = $this->retry_comment( $comment_id );
            if ( false === $retry ) {
                return;
            }
            $commentiq_comment_id = get_comment_meta( $comment_id, $this->comment_id_meta_key, true );
            if ( false == $commentiq_comment_id || empty( $commentiq_comment_id ) ) {
                return;
            }
        }

        $comment_details = $this->api_client->update_comment($commentiq_comment_id, $comment->comment_content, $comment->comment_date_gmt, $comment->comment_author);

        if ($comment_details instanceof WP_Error) {
            return;
        }

        $this->update_comment_details($comment_id, $comment_details);

        $post = get_post($comment->comment_post_ID);

        if ($post instanceof WP_Post) {
            $this->update_elevated_comment($post);
        }
    }

    /**
     * Adds a new comment with the Comment IQ API.
     *
     * @param int  $comment_id
     * @param bool return true on success, false on failure
     */
    public function on_comment_new($comment_id)
    {
        $comment = get_comment($comment_id);

        if (!$comment instanceof WP_Comment || !$this->is_valid_comment($comment)) {
            return false;
        }

        $post = get_post($comment->comment_post_ID);

        if (!$post instanceof WP_Post) {
            return false;
        }

        $article_id = get_post_meta($post->ID, $this->article_id_meta_key, true);

        if ( false === $article_id || empty( $article_id ) ) {
	        $this->on_post_save( $post->ID, $post );
	        $article_id = get_post_meta($post->ID, $this->article_id_meta_key, true);
        }

        if (!is_numeric($article_id)) {
            return false;
        }

        $comment_details = $this->api_client->add_comment($article_id, $comment->comment_content, $comment->comment_date_gmt, $comment->comment_author);

        if ($comment_details instanceof WP_Error) {
            return false;
        }

        $this->update_comment_details( $comment_id, $comment_details );
        $this->update_elevated_comment( $post );

        return true;
    }

    /**
     * Adds or updates an article with the Comment IQ API when WordPress saves a post.
     *
     * @param int     $post_id
     * @param WP_Post $post
     */
    public function on_post_save($post_id, WP_Post $post)
    {
        if (!in_array($post->post_type, $this->post_types)) {
            return;
        }

        $article_id = get_post_meta($post_id, $this->article_id_meta_key, true);

        if (empty($article_id)) {
            $article_id = $this->api_client->add_article($post->post_content);
        } elseif (is_numeric($article_id)) {
            $this->api_client->update_article($article_id, $post->post_content);
        }

        if (is_numeric($article_id)) {
             update_post_meta($post_id, $this->article_id_meta_key, $article_id);
        }
    }

    /**
     * Compares a potential elevated comment to another WordPress comment.
     *
     * To be considered, a comment must be shorter than 100 characters. If the given
     * comment doesn't match this criteria. The elevated comment is returned. Otherwise, the
     * method will compare the two comments and return the one with the highest relevance.
     *
     * [This method is for use with the `array_reduce` function.]
     *
     * @param mixed      $elevated_comment
     * @param WP_Comment $comment
     *
     * @return mixed
     */
    private function compare_comments($elevated_comment, WP_Comment $comment)
    {
        if (!$this->is_valid_comment($comment)) {
            return $elevated_comment;
        }

        $comment_details = get_comment_meta($comment->comment_ID, $this->comment_details_meta_key, true);

        if (!is_array($comment_details)
            || !isset($comment_details['Length'], $comment_details['ArticleRelevance'])
            || $comment_details['Length'] < 35
            || $comment_details['Length'] > 175
            || $comment_details['ArticleRelevance'] == 0
        ) {
            return $elevated_comment;
        }

        if (!$elevated_comment instanceof WP_Comment) {
            return $comment;
        }

        $elevated_comment_details = get_comment_meta($elevated_comment->comment_ID, $this->comment_details_meta_key, true);

        if ($comment_details['ArticleRelevance'] > $elevated_comment_details['ArticleRelevance']) {
            $elevated_comment = $comment;
        }

        return $elevated_comment;
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
     * Update the comment details from the Comment IQ API for the given comment ID.
     *
     * @param  int   $comment_id
     * @param  array $comment_details
     */
    private function update_comment_details($comment_id, array $comment_details)
    {
        if (isset($comment_details['commentID'])) {
            update_comment_meta($comment_id, $this->comment_id_meta_key, $comment_details['commentID']);
            unset($comment_details['commentID']);
        }

        update_comment_meta($comment_id, $this->comment_details_meta_key, $comment_details);
    }

    /**
     * Update the elevated comment for the given post.
     *
     * @param WP_Post $post
     */
    private function update_elevated_comment(WP_Post $post)
    {
        $comment_count = wp_count_comments($post->ID);

        if (!is_object($comment_count) || $comment_count->approved < 3) {
            return;
        }

        $elevated_comment = array_reduce(get_comments(array('post_id' => $post->ID)), array($this, 'compare_comments'));
        
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
        if ( $post->post_author === $elevated_comment->user_id && ! $allow_post_author ) {
	        return;
        }

        if ($elevated_comment instanceof WP_Comment) {
            update_post_meta($post->ID, $this->elevated_comment_id_meta_key, $elevated_comment->comment_ID);
        }
    }
}
