<?php

namespace WPMVC\MVC\Traits;

/**
 * Trait related to all meta functionality of a model.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @author Cami Mostajo
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.2
 */
trait MetaTrait
{
    /**
     * Meta data.
     * @since 1.0.0
     * @var array
     */
    protected $meta = array();
    /**
     * Flag that indicates if model should decode meta string values identified as JSON.
     * @since 2.1.1
     * @var bool
     */
    protected $decode_json_meta = true;
    /**
     * Loads meta values into objet.
     * @since 1.0.0
     * @since 2.1.0 Unserialize method changed.
     * @since 2.1.1 Fixes casting issues.
     *
     * @see https://codex.wordpress.org/Function_Reference/maybe_unserialize
     * @see https://eval.in/966124
     * @see http://php.net/manual/en/function.json-last-error.php
     */
    public function load_meta()
    {
        if ( empty( $this->attributes ) ) return;
        foreach ( get_post_meta( $this->attributes['ID'] ) as $key => $value ) {
            if ( ! preg_match( '/_wp_/', $key )
                || in_array( 'meta_' . $key, $this->aliases )
            ) {
                $value = $value[0];
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
     * @since 1.0.0
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
     * Sets meta value.
     * @since 1.0.0
     *
     * @param string $key   Key.
     * @param mixed  $value Value.
     */
    public function set_meta( $key, $value )
    {
        $this->meta[$key] = $value;
    }
    /**
     * Gets value from meta.
     * @since 1.0.0
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
     * Deletes meta.
     * @since 1.0.0
     *
     * @param string $key Key.
     */
    public function delete_meta( $key )
    {
        if ( ! $this->has_meta( $key ) ) return;
        delete_post_meta( $this->attributes['ID'], $key );
        unset( $this->meta[$key] );
    }
    /**
     * Either adds or updates a meta.
     * @since 1.0.0
     * @since 1.0.1 Hotfix, only saves registered meta.
     * @since 2.1.1 Serialization changed.
     * @since 2.1.2 Removed serialization, already done by WordPress.
     *
     * @see https://codex.wordpress.org/Function_Reference/maybe_serialize
     *
     * @param string $key   Key.
     * @param mixed  $value Value.
     */
    public function save_meta( $key, $value, $update_array = true )
    {
        if ( preg_match( '/_wp_/', $key ) ) return;
        if ( ! in_array( 'meta_' . $key, $this->aliases ) ) return;
        if ( $update_array )
            $this->meta[$key] = $value;
        update_post_meta( $this->attributes['ID'], $key, $value );
    }
    /**
     * Saves all meta values.
     * @since 1.0.0
     */
    public function save_meta_all()
    {
        foreach ( $this->meta as $key => $value ) {
            $this->save_meta( $key, $value, false );
        }
    }
}