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

if (!defined('ARRAY_A'))
    define('ARRAY_A', true);

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

function get_user_by($key, $id)
{
    return new WP_User($id);
}

function get_post($id, $output = ARRAY_A)
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

function get_post_meta($ID, $key = '', $row = true)
{
    return empty($key) ? [] : '"1"';
}

function get_user_meta($ID, $key = '', $row = true)
{
    return empty($key) ? [] : '"1"';
}

function delete_user_meta($ID, $key)
{
    return true;
}

function update_user_meta($ID, $key, $value)
{
    return true;
}

function wp_insert_user($data)
{
    return true;
}

function is_wp_error($error)
{
    return is_a($error, 'WP_Error');
}

function get_option($key)
{
    if ($key == 'model_test')
        return '{"ID":"test","a":"A value","b":"B value","isSetup":false}';
    return;
}

function update_option($key, $value)
{
    return true;
}

function delete_option($key)
{
    return true;
}