<?php

/*
Plugin Name: Elevated Comments
Description: Analyze comments to determine which are most articulate & relevant. Place them near the top of the post.
Author: Postmatic
Version: 1.1.6
Author URI: https://gopostmatic.com
License: GPL2
Text Domain: elevated-comments
Domain Path: /languages
*/

define( 'ELEVATED_COMMENTS_DIR_NAME', plugin_basename(__FILE__) );

function elevated_comments_text_domain() {
    load_plugin_textdomain( 'elevated-comments', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

// Setup class autoloader
require_once dirname(__FILE__) . '/src/CommentIQ/Autoloader.php';
CommentIQ_Autoloader::register();

// Load Comment IQ
$commentiq_plugin = new CommentIQ_Plugin(__FILE__);
add_action( 'plugins_loaded', array( $commentiq_plugin, 'load' ) );
add_action( 'plugins_loaded', 'elevated_comments_text_domain' );
