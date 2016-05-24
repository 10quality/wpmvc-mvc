<?php

namespace WPMVC\MVC\Models;

use WPMVC\MVC\Contracts\Modelable;
use WPMVC\MVC\Contracts\Findable;
use WPMVC\MVC\Contracts\Arrayable;
use WPMVC\MVC\Contracts\JSONable;
use WPMVC\MVC\Contracts\Stringable;
use WPMVC\MVC\Contracts\Metable;
use WPMVC\MVC\Traits\AliasTrait;
use WPMVC\MVC\Traits\CastTrait;
use WPMVC\MVC\Traits\SetterTrait;
use WPMVC\MVC\Traits\GetterTrait;
use WPMVC\MVC\Traits\ArrayCastTrait;

/**
 * User model.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
abstract class UserModel implements Modelable, Findable, Metable, JSONable, Stringable, Arrayable
{
    use AliasTrait, CastTrait, SetterTrait, GetterTrait, ArrayCastTrait;

    /**
     * Attributes.
     * @since 1.0
     * @var array
     */
    protected $attributes = [];

    /**
     * Meta data.
     * @since 1.0
     * @var array
     */
    protected $meta = [];

    /**
     * Hidden properties.
     * @since 1.0
     * @var array
     */
    protected $hidden = [];

    /**
     * User WP data
     * @since 1.0
     * @var mixed
     */
    protected $data = null;

    /**
     * Default constructor.
     * @since 1.0
     *
     * @param int $id User ID.
     */
    public function __construct( $id = null )
    {
        if ( ! empty( $id ) ) {
            $this->load( $id );
        }
    }

    /**
     * Static constructor.
     * Returns a user by ID.
     * @since 1.0
     *
     * @param int $id User ID.
     *
     * @return mixed
     */
    public static function find( $id = null )
    {
        $user = new self( $id );
        if ( $user )
            return $user;
        return;
    }

    /**
     * Returns current user.
     * @since 1.0
     *
     * @return mixed
     */
    public static function current()
    {
        return self::find( get_current_user_id() );
    }

    /**
     * Loads user data.
     * @since 1.0
     *
     * @param int $id User ID.
     */
    public function load( $id )
    {
        if ( ! empty( $id ) ) {
            $this->data = get_user_by( 'id', $id );
            $this->load_meta();
        }
    }

    /**
     * Deletes user.
     * @since 1.0
     */
    public function delete()
    {
        // TODO
    }

    /**
     * Saves user.
     * Returns success flag.
     * @since 1.0
     *
     * @return bool
     */
    public function save()
    {
        $this->save_meta_all();
        return true;
    }

    /**
     * Loads user meta data.
     * @since 1.0
     */
    public function load_meta()
    {
        if ( $this->ID ) {
            foreach ( get_user_meta( $this->ID ) as $key => $value ) {
                $value = is_array( $value ) ? $value[0] : $value;

                $this->meta[$key] = is_string( $value )
                    ? ( preg_match( '/\{|\[/', $value )
                        ? (array)json_decode( $value )
                        : $value
                    )
                    : ( is_integer( $value )
                        ? intval( $value )
                        : floatval( $value )
                    );
            }
        }
    }

    /**
     * Returns flag indicating if object has meta key.
     * @since 1.0
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
     * @since 1.0
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
     * @since 1.0
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
     * @since 1.0
     *
     * @param string $key Key.
     */
    public function delete_meta( $key )
    {
        if ( ! $this->has_meta( $key ) ) return;

        delete_user_meta( $this->ID, $key );

        unset( $this->meta[$key] );
    }

    /**
     * Either adds or updates a meta.
     * @since 1.0
     *
     * @param string $key   Key.
     * @param mixed  $value Value.
     */
    public function save_meta( $key, $value, $update_array = true )
    {   
        if ( $update_array )
            $this->meta[$key] = $value;

        update_user_meta( 
            $this->ID,
            $key,
            is_array( $value )
                ? json_encode( $value )
                : ( is_object( $value )
                    ? json_encode( (array)$value )
                    : $value
                )
        );
    }

    /**
     * Saves all meta values.
     * @since 1.0
     */
    public function save_meta_all()
    {
        foreach ( $this->meta as $key => $value ) {
            // Save only defined meta
            if ( in_array( 'meta_' . $key, $this->aliases ) )
                $this->save_meta( $key, $value, false );
        }
    }
}
