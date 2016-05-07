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
 * CommentIQ Plugin.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
class CommentIQ_Plugin
{
    /**
     * The client used to connect to the Comment IQ API.
     *
     * @var CommentIQ_API_Client
     */
    private $api_client;

    /**
     * The plugin event manager.
     *
     * @var CommentIQ_EventManagement_EventManager
     */
    private $event_manager;

    /**
     * Flag to track if the plugin is loaded.
     *
     * @var bool
     */
    private $loaded;

    /**
     * Absolute path to the directory where WordPress installed the plugin.
     *
     * @var string
     */
    private $plugin_path;

    /**
     * URL to the directory where WordPress installed the plugin.
     *
     * @var string
     */
    private $plugin_url;

    /**
     * Constructor.
     *
     * @param string $file
     */
    public function __construct($file)
    {
        $this->api_client = new CommentIQ_API_Client(_wp_http_get_object());
        $this->event_manager = new CommentIQ_EventManagement_EventManager();
        $this->loaded = false;
        $this->plugin_path = plugin_dir_path($file);
        $this->plugin_url = plugin_dir_url($file);
    }

    /**
     * Checks if the plugin is loaded.
     *
     * @return bool
     */
    public function is_loaded()
    {
        return $this->loaded;
    }

    /**
     * Loads the plugin into WordPress.
     */
    public function load()
    {
        if ($this->is_loaded()) {
            return;
        }

        foreach($this->get_shortcodes() as $shortcode) {
            $this->register_shortcode($shortcode);
        }

        foreach ($this->get_subscribers() as $subscriber) {
            $this->event_manager->add_subscriber($subscriber);
        }
        
        // Settings Page
        $settings_page = new CommentIQ_Admin_Settings();
        $settings_page->run();

        $this->loaded = true;
    }

    /**
     * Get the elevated comment generator.
     *
     * @return CommentIQ_Generator_ElevatedCommentGenerator
     */
    private function get_elevated_comment_generator()
    {
        return new CommentIQ_Generator_ElevatedCommentGenerator($this->plugin_path . 'assets/templates/elevated-comment.php', $this->get_supported_post_types());
    }

    /**
     * Get the plugin shortcodes.
     *
     * @return CommentIQ_Shortcode_ShortcodeInterface[]
     */
    private function get_shortcodes()
    {
        return array(
            new CommentIQ_Shortcode_ElevatedCommentShortcode($this->get_elevated_comment_generator(), $this->get_supported_post_types()),
        );
    }

    /**
     * Get the plugin event subscribers.
     *
     * @return CommentIQ_EventManagement_SubscriberInterface[]
     */
    private function get_subscribers()
    {
        return array(
            new CommentIQ_Subscriber_AssetsSubscriber($this->plugin_url . 'assets/', $this->get_supported_post_types()),
            new CommentIQ_Subscriber_PostmaticAssetsSubscriber($this->plugin_path . 'assets/' ),
            new CommentIQ_Subscriber_AutomatedElevatedCommentSubscriber($this->get_elevated_comment_generator(), $this->get_supported_post_types()),
            new CommentIQ_Subscriber_CommentIQAPISubscriber($this->api_client, $this->get_supported_post_types()),
        );
    }

    /**
     * Get the supported post types for the plugin.
     *
     * @return array
     */
    private function get_supported_post_types()
    {
        return array('post');
    }

    /**
     * Register the given shortcode with the WordPress shortcode API.
     *
     * @param CommentIQ_Shortcode_ShortcodeInterface $shortcode
     */
    private function register_shortcode(CommentIQ_Shortcode_ShortcodeInterface $shortcode)
    {
        add_shortcode($shortcode::get_name(), array($shortcode, 'generate_output'));
    }
}
