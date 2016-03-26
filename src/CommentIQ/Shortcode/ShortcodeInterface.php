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
 * A shortcode represents the shortcode registered with the WordPress shortcode API.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
interface CommentIQ_Shortcode_ShortcodeInterface
{
    /**
     * Get the tag name used by the shortcode.
     *
     * @return string
     */
    public static function get_name();

    /**
     * Generate the output of the shortcode.
     *
     * @param array|string  $attributes
     * @param string        $content
     *
     * @return string
     */
    public function generate_output($attributes, $content = '');
}
