<?php

namespace WPMVC\MVC\Models;

use WP_Term;
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
 * Post Category Model.
 *
 * @author Cami M
 * @copyright 10 Quality Studio <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.11
 */
abstract class TermModel implements Modelable, Findable, Metable, JSONable, Stringable, Arrayable, Traceable
{
    use AliasTrait, CastTrait, SetterTrait, GetterTrait, ArrayCastTrait;
    /**
     * Attributes in model.
     * @since 2.1.5
     * @var array
     */
    protected $attributes = array();
    /**
     * Attributes and aliases hidden from print.
     * @since 2.1.5
     * @var array
     */
    protected $hidden = array();
    /**
     * Meta data.
     * @since 2.1.5
     * @var array
     */
    protected $meta = array();
    /**
     * Taxonomy to use in model.
     * @since 2.1.5
     * @var string
     */
    protected $model_taxonomy = null;
    /**
     * Flag that indicates if model should decode meta string values identified as JSON.
     * @since 2.1.5
     * @var bool
     */
    protected $decode_json_meta = true;
    /**
     * Default constructor.
     * @since 1.0.0
     *
     * @param string $taxonomy Model taxonomy.
     * @param int    $id       Term ID.
     */
    public function __construct( $taxonomy = null, $id = null )
    {
        if ( $taxonomy )
            $this->model_taxonomy = $taxonomy;
        $this->load( $id );
    }
    /**
     * Loads category data.
     * @since 2.1.5
     *
     * @param int $id User ID.
     */
    public function load( $id )
    {
        if ( ! empty( $id ) ) {
            $this->attributes = get_term( $id, $this->model_taxonomy, ARRAY_A );
            $this->load_meta();
        }
    }
    /**
     * Loads term data.
     * @since 1.0.3
     *
     * @param string $slug Term slug.
     */
    public function load_by_slug( $slug )
    {
        if ( ! empty( $slug ) ) {
            $this->attributes = get_term_by( 'slug', $slug, $this->model_taxonomy, ARRAY_A );
            $this->load_meta();
        }
    }
    /**
     * Loads terms from a WP_Term object.
     * @since 1.0.0
     * 
     * @param WP_Term $term
     * 
     * @return this
     */
    public function from_term( WP_Term $term )
    {
        $this->model_taxonomy = $term->taxonomy;
        $this->attributes = (array)$term;
        $this->load_meta();
        return $this;
    }
    /**
     * Loads terms from an array.
     * @since 1.0.0
     * 
     * @param array $term
     * 
     * @return this
     */
    public function from_array( $term )
    {
        if ( isset( $term['taxonomy'] ) )
        $this->model_taxonomy = $term['taxonomy'];
        $this->attributes = $term;
        $this->load_meta();
        return $this;
    }
    /**
     * Deletes category.
     * @since 2.1.5
     */
    public function delete()
    {
        wp_delete_term( $this->name, $this->taxonomy );
    }
    /**
     * Saves user.
     * Returns success flag.
     * @since 2.1.5
     *
     * @return bool
     */
    public function save()
    {
        $id = $this->term_id;
        $data = [];
        if ( $this->term_id ) {
            foreach ( $this->attributes as $key => $value ) {
                if ( $key === 'term_id' || $key === 'taxonomy' )
                    continue;
                $data[$key] = $value;
            }
            $id = wp_update_term( $this->term_id, $this->model_taxonomy, $data );
        } else {
            foreach ( $this->attributes as $key => $value ) {
                if ( $key === 'name' )
                    continue;
                $data[$key] = $value;
            }
            $id = wp_insert_term( $this->name, $this->model_taxonomy, $data );
        }
        if ( is_wp_error( $id ) )
            return false;
        $this->term_id = $id;
        $this->save_meta_all();
        return true;
    }
    /**
     * Loads user meta data.
     * @since 2.1.5
     */
    public function load_meta()
    {
        if ( $this->term_id ) {
            foreach ( get_term_meta( $this->term_id ) as $key => $value ) {
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
        } else {
            $this->attributes['taxonomy'] = 'category';
        }
    }
    /**
     * Returns flag indicating if object has meta key.
     * @since 2.1.5
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
     * @since 2.1.5
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
     * @since 2.1.5
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
     * @since 2.1.5
     *
     * @param string $key Key.
     */
    public function delete_meta( $key )
    {
        if ( ! $this->has_meta( $key ) ) return;
        delete_term_meta( $this->term_id, $key );
        unset( $this->meta[$key] );
    }
    /**
     * Either adds or updates a meta.
     * @since 2.1.5
     *
     * @param string $key   Key.
     * @param mixed  $value Value.
     */
    public function save_meta( $key, $value, $update_array = true )
    {   
        if ( $update_array )
            $this->set_meta($key, $value);
        try {
            update_term_meta( $this->term_id, $key, $value );
        } catch ( Exception $e ) {
            Log::error( $e );
        }
    }
    /**
     * Saves all meta values.
     * @since 2.1.5
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
        return $this->term_id !== null;
    }
}