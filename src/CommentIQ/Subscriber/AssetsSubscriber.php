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
 * Event subscriber that registers assets with WordPress.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
class CommentIQ_Subscriber_AssetsSubscriber implements CommentIQ_EventManagement_SubscriberInterface
{
    /**
     * URL to the directory where the plugin assets are stored.
     *
     * @var string
     */
    private $assets_url;

    /**
     * The post types that we want to insert elevated comments into.
     *
     * @var array
     */
    private $post_types;

    /**
     * Constructor.
     *
     * @param string $assets_url
     * @param array  $post_types
     */
    public function __construct($assets_url, array $post_types = array())
    {
        $this->assets_url = $assets_url;
        $this->post_types = $post_types;
    }

    /**
     * {@inheritdoc}
     */
    public static function get_subscribed_events()
    {
        return array(
            'wp_enqueue_scripts' => 'add_frontend_assets',
        );
    }

    /**
     * Add assets for the WordPress frontend.
     */
    public function add_frontend_assets()
    {
        if (!is_front_page() && !is_singular($this->post_types)) {
            return;
        }

        wp_enqueue_style('elevated-comment', $this->assets_url . 'css/elevated-comment.css', array(), '20160509', 'all' );
    }
}
