<?php

/*
Plugin Name: Elevated Comments
Description: Analyze comments to determine which are most articulate & relevant. Place them near the top of the post.
Author: Postmatic
Version: 1.1.0
Author URI: https://gopostmatic.com
License: GPL2
*/

// Setup class autoloader
require_once dirname(__FILE__) . '/src/CommentIQ/Autoloader.php';
CommentIQ_Autoloader::register();

// Load Comment IQ
$commentiq_plugin = new CommentIQ_Plugin(__FILE__);
add_action('plugins_loaded', array($commentiq_plugin, 'load'));
