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
 * @author Dylan Kuhn <dylan@gopostmatic.com>
 */
class CommentIQ_Subscriber_PostmaticAssetsSubscriber implements CommentIQ_EventManagement_SubscriberInterface {
    /**
     * Path to the directory where the plugin assets are stored.
     *
     * @var string
     */
    private $assets_path;

    /**
     * Constructor.
     *
     * @param string $assets_path
     */
    public function __construct( $assets_path ) {
        $this->assets_path = $assets_path;
    }

    /**
     * {@inheritdoc}
     */
    public static function get_subscribed_events() {
        return array(
            'prompt/html_email/print_styles' => 'print_styles',
        );
    }

    /**
     * Echo styles for Postmatic HTML emails.
     */
    public function print_styles() {
        @readfile( path_join( $this->assets_path, 'css/elevated-comment.css' ) );
    }
}
