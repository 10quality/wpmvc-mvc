<?php

require_once __DIR__.'/classes/wp_user.php';
require_once __DIR__.'/classes/wp_post.php';

/**
 * Wordpress compatibility functions.
 * Emulates wordpress functions for testing purposes.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */

function get_template_directory()
{
    return __DIR__.'/theme/';
}

function get_current_user_id()
{
    return 404;
}

function get_userdata($id)
{
    return new WP_User($id);
}

function get_post($id, $output)
{
    $post = new WP_Post($id);
    return $output === ARRAY_A ? (array)$post : $post;
}

function wp_insert_post($args, &$error)
{
    $error = isset($args->ID) && in_array($args->ID, [1,2,3,4,5,6,7]);
}

function wp_delete_post($ID, $force = true)
{
    return true;
}