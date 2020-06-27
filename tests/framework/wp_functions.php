<?php

require_once __DIR__.'/classes/wp_user.php';
require_once __DIR__.'/classes/wp_post.php';
require_once __DIR__.'/classes/wp_term.php';
require_once __DIR__.'/classes/wp_error.php';
require_once __DIR__.'/classes/wp_comment.php';

/**
 * WordPress compatibility functions.
 * Emulates WordPress functions for testing purposes.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.5
 */
$data = null;
if (!defined('ARRAY_A'))
    define('ARRAY_A', true);
if (!defined('DOING_AUTOSAVE'))
    define('DOING_AUTOSAVE', false);
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
    return $id > 100000 ? false : new WP_User($id);
}
function get_post($id, $output = ARRAY_A)
{
    if ( $id > 1000000 ) return null;
    $post = new WP_Post($id);
    return $output === ARRAY_A ? (array)$post : $post;
}
function wp_insert_post($args)
{
    return isset($args['ID']) ? new WP_Error : rand(1,20);
}
function wp_update_post($args)
{
    return !isset($args['ID']) || $args['ID'] > 20 ? new WP_Error : $args['ID'];
}
function wp_cache_delete()
{
    return true;
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
function update_post_meta($ID, $key, $value)
{
    return true;
}
function update_user_meta($ID, $key, $value)
{
    return true;
}
function wp_insert_user($data)
{
    $data['trigger'] = 'wp_insert_user';
    $GLOBALS['data'] = $data;
    return 707;
}
function wp_update_user($data)
{
    $data['trigger'] = 'wp_update_user';
    $GLOBALS['data'] = $data;
    return true;
}
function wp_delete_user($id)
{
    $GLOBALS['data'] = ['trigger' => 'wp_delete_user', 'ID' => $id];
    return true;
}
function is_wp_error($error)
{
    return is_a($error, WP_Error::class);
}
function get_option($key)
{
    if ($key == 'model_test')
        return '{"ID":"test","a":"A value","b":"B value","isSetup":false}';
    return;
}
function update_option($key, $value)
{
    $GLOBALS['data'] = ['option_name' => $key, 'option_value' => $value];
    return true;
}
function delete_option($key)
{
    return true;
}
function get_category($ID)
{
    $cat = new stdClass;
    $cat->term_id = $ID;
    $cat->cat_ID = $ID;
    $cat->name = 'Category';
    $cat->slug = 'category';
    $cat->taxonomy = 'category';
    return $cat;
}
function update_term_meta($ID, $key, $value)
{
    return true;
}
function delete_term_meta($ID, $key)
{
    return true;
}
function get_term_meta($ID, $key = '', $row = true)
{
    return empty($key) ? [] : '"1"';
}
function wp_insert_term($name, $tax, $args)
{
    return rand();
}
function wp_delete_term($term, $tax)
{
    return true;
}
function do_action($hook, $callback)
{
    return true;
}
function apply_filters($hook, $value)
{
    return $value;
}
function wp_nonce_field($key, $nonce)
{
    return true;
}
function wp_verify_nonce($nonce, $key)
{
    return true;
}
function get_stylesheet_directory()
{
    return __DIR__.'/theme/';
}
function get_post_thumbnail_id( $id )
{
    return 1500;
}
function wp_upload_dir()
{
    return array(
        'path'      => 'C:\test/wp-content/uploads',
        'url'       => 'http://test/wp-content/uploads',
        'subdir'    => '',
        'basedir'   => 'C:\test/wp-content/uploads',
        'baseurl'   => 'http://test/wp-content/uploads',
        'error'     => null,
    );
}
function maybe_unserialize($value)
{
    return $value;
}
function maybe_serialize($value)
{
    return $value;
}
function get_terms( $tax, $args )
{
    return [new WP_Term(1, $tax),new WP_Term(2, $tax)];
}
function get_term( $id, $tax )
{
    if ( $id > 1000000 ) return null;
    return ['term_id' => $id, 'slug' => 'term-'.$id, 'name' => 'Term ID:'.$id, 'taxonomy' => $tax];
}
function get_term_by( $prop, $slug, $tax )
{
    if ( $slug === 'error' ) return null;
    return ['term_id' => 404, 'slug' => $slug, 'name' => ucfirst( $slug ), 'taxonomy' => $tax];
}
function wp_update_term($id, $tax, $args)
{
    $GLOBALS['data'] = $args;
    return $id;
}
function get_comment( $id, $output = false )
{
    if ( $id > 1000000 ) return null;
    $comment = new WP_Comment($id);
    return $output === ARRAY_A ? (array)$comment : $comment;
}
function wp_insert_comment($data)
{
    $data['trigger'] = 'wp_insert_comment';
    $GLOBALS['data'] = $data;
    return 101;
}
function wp_update_comment($data)
{
    $data['trigger'] = 'wp_update_comment';
    $GLOBALS['data'] = $data;
    return true;
}
function wp_delete_comment($id)
{
    $GLOBALS['data'] = ['trigger' => 'wp_delete_comment', 'comment_ID' => $id];
    return true;
}
function delete_comment_meta($ID, $key)
{
    return true;
}
function update_comment_meta($ID, $key, $value)
{
    return true;
}
function get_comment_meta($ID)
{
    return ['views' => 99];
}