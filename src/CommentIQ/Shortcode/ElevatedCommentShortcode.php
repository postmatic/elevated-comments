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
 * Shortcode that generates an elevated comment using the elevated comment
 * generator.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
class CommentIQ_Shortcode_ElevatedCommentShortcode implements CommentIQ_Shortcode_ShortcodeInterface
{
    /**
     * The elevated comment generator.
     *
     * @var CommentIQ_Generator_ElevatedCommentGenerator
     */
    private $elevated_comment_generator;

    /**
     * The post types that can use the elevated comment shortcode.
     *
     * @var array
     */
    private $post_types;

    /**
     * Constructor.
     *
     * @param CommentIQ_Generator_ElevatedCommentGenerator $elevated_comment_generator
     * @param array                                        $post_types
     */
    public function __construct(CommentIQ_Generator_ElevatedCommentGenerator $elevated_comment_generator, array $post_types = array())
    {
        $this->elevated_comment_generator = $elevated_comment_generator;
        $this->post_types = $post_types;
    }

    /**
     * {@inheritdoc}
     */
    public static function get_name()
    {
        return 'elevated-comment';
    }

    /**
     * {@inheritdoc}
     */
    public function generate_output($attributes, $content = '')
    {
        if (!is_main_query()) {
            return '';
        }

        $post = get_post();

        if (!$post instanceof WP_Post || !in_array($post->post_type, $this->post_types)) {
            return '';
        }

        return $this->elevated_comment_generator->generate($post);
    }
}
