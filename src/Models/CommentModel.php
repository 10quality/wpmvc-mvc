<?php

namespace WPMVC\MVC\Models;

use WP_Comment;
use WPMVC\MVC\Contracts\Modelable;
use WPMVC\MVC\Contracts\Findable;
use WPMVC\MVC\Contracts\Arrayable;
use WPMVC\MVC\Contracts\JSONable;
use WPMVC\MVC\Contracts\Stringable;
use WPMVC\MVC\Contracts\Metable;
use WPMVC\MVC\Contracts\Traceable;
use WPMVC\MVC\Traits\AliasTrait;
use WPMVC\MVC\Traits\CastTrait;
use WPMVC\MVC\Traits\SetterTrait;
use WPMVC\MVC\Traits\GetterTrait;
use WPMVC\MVC\Traits\ArrayCastTrait;

/**
 * Comment Model.
 *
 * @author Cami M
 * @copyright 10 Quality Studio <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.11
 */
abstract class CommentModel implements Modelable, Findable, Metable, JSONable, Stringable, Arrayable, Traceable
{
    use AliasTrait, CastTrait, SetterTrait, GetterTrait, ArrayCastTrait;
    /**
     * Attributes in model.
     * @since 2.1.11
     * @var array
     */
    protected $attributes = array();
    /**
     * Attributes and aliases hidden from print.
     * @since 2.1.11
     * @var array
     */
    protected $hidden = array();
    /**
     * Meta data.
     * @since 2.1.11
     * @var array
     */
    protected $meta = array();
    /**
     * Posts are moved to trash when on soft delete.
     * @since 2.1.11
     * @var bool
     */
    protected $forceDelete = false;
    /**
     * WordPress comment object.
     * @since 2.1.11
     * @var \WP_Comment|null
     */
    protected $__wp_comment;
    /**
     * Flag that indicates if model should decode meta string values identified as JSON.
     * @since 2.1.11
     * @var bool
     */
    protected $decode_json_meta = true;
    /**
     * Default constructor.
     * @since 2.1.11
     *
     * @param int $id Comment ID.
     */
    public function __construct( $id = null )
    {
        if ( ! empty( $id ) ) {
            $this->load( $id );
        }
    }
    /**
     * Loads user data.
     * @since 2.1.11
     *
     * @param int $id Comment ID.
     */
    public function load( $id )
    {
        if ( ! empty( $id ) ) {
            $this->load_wp_comment( get_comment( $id ) );
        }
    }
    /**
     * Loads model from a user object.
     * @since 2.1.11
     * 
     * @param \WP_Comment $user
     */
    public function load_wp_comment( $comment )
    {
        if ( !is_object( $comment ) || !$comment instanceof WP_Comment )
            return;
        $this->__wp_comment = $comment;
        if ( $this->__wp_comment ) {
            $this->attributes = $this->__wp_comment->to_array();
            $this->load_meta();
        }
    }
    /**
     * Loads model from an array.
     * @since 2.1.11
     * 
     * @param array $term
     * 
     * @return this
     */
    public function from_array( $attributes )
    {
        if ( !is_array( $attributes ) )
            return;
        $this->attributes = $attributes;
        $this->load_meta();
        return $this;
    }
    /**
     * Returns WordPress comment object attached to model.
     * @since 2.1.11
     * 
     * @return \WP_Comment|null
     */
    public function wp_comment()
    {
        return isset( $this->__wp_comment ) ? $this->__wp_comment : null;
    }
    /**
     * Deletes comment.
     * @since 2.1.11
     * 
     * @return bool
     */
    public function delete()
    {
        return $this->comment_ID ? wp_delete_comment( $this->comment_ID, $this->forceDelete ) : false;
    }
    /**
     * Saves comment.
     * Returns success flag.
     * @since 2.1.11
     *
     * @return bool
     */
    public function save()
    {
        // Insert or update
        if ( $this->comment_ID === null ) {
            $id = wp_insert_comment( $this->attributes );
            if ( is_wp_error( $id ) )
                return false;
            $this->comment_ID = $id;
        } else {
            $aliases = array_filter( $this->aliases, function( $alias ) {
                return strpos( $alias, 'meta_' ) === false && strpos( $alias, 'func_' ) === false;
            } );
            $id = wp_update_comment( array_filter( $this->attributes, function( $key ) use( &$aliases ) {
                return $key === 'comment_ID' || in_array( $key, $aliases );
            }, ARRAY_FILTER_USE_KEY ) );
            if ( is_wp_error( $id ) || $id === 0 )
                return false;
        }
        // Save meta
        $this->save_meta_all();
        return true;
    }
    /**
     * Loads comment meta data.
     * @since 2.1.11
     */
    public function load_meta()
    {
        if ( $this->comment_ID ) {
            foreach ( get_comment_meta( $this->comment_ID ) as $key => $value ) {
                $value = is_array( $value ) ? $value[0] : $value;
                // Check for json string
                if ( $this->decode_json_meta
                    && is_string( $value )
                    && preg_match( '/(\{|\[|\")(?:[^{}]|(?R))*(\}|\]|\")/', $value )
                ) {
                    $this->meta[$key] = json_decode( $value );
                    if ( json_last_error() === JSON_ERROR_NONE )
                        continue; // Break loop
                }
                $this->meta[$key] = maybe_unserialize( $value );
            }
        }
    }
    /**
     * Returns flag indicating if object has meta key.
     * @since 2.1.11
     *
     * @param string $key Key.
     *
     * @return bool
     */
    public function has_meta( $key )
    {
        return array_key_exists( $key, $this->meta );
    }
    /**
     * Gets value from meta.
     * @since 2.1.11
     *
     * @param string $key Key.
     *
     * @return mixed.
     */
    public function get_meta( $key )
    {
       return $this->has_meta( $key ) ? $this->meta[$key] : null;
    }
    /**
     * Sets meta value.
     * @since 2.1.11
     *
     * @param string $key   Key.
     * @param mixed  $value Value.
     */
    public function set_meta( $key, $value )
    {
        $this->meta[$key] = $value;
    }
    /**
     * Deletes meta.
     * @since 2.1.11
     *
     * @param string $key Key.
     */
    public function delete_meta( $key )
    {
        if ( ! $this->has_meta( $key ) ) return;
        delete_comment_meta( $this->comment_ID, $key );
        unset( $this->meta[$key] );
    }
    /**
     * Either adds or updates a meta.
     * @since 2.1.11
     *
     * @param string $key   Key.
     * @param mixed  $value Value.
     */
    public function save_meta( $key, $value, $update_array = true )
    {   
        if ( $update_array )
            $this->set_meta($key, $value);
        try {
            update_comment_meta( $this->comment_ID, $key, $value );
        } catch ( Exception $e ) {
            Log::error( $e );
        }
    }
    /**
     * Saves all meta values.
     * @since 2.1.11
     */
    public function save_meta_all()
    {
        foreach ( $this->meta as $key => $value ) {
            // Save only defined meta
            if ( in_array( 'meta_' . $key, $this->aliases ) )
                $this->save_meta( $key, $value, false );
        }
    }
    /**
     * Returns flag indicating if model has a trace in the database (an ID).
     * @since 2.1.11
     *
     * @param bool
     */
    public function has_trace()
    {
        return $this->comment_ID !== null;
    }
}