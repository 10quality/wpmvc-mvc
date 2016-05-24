<?php

namespace WPMVC\MVC\Traits;

/**
 * Generic model trait.
 * Provides generic model functionality.
 * Perfect for models based on option, category or user.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
trait GenericModelTrait
{
    /**
     * Getter function.
     * @since 1.0.0
     *
     * @param string $property
     *
     * @return mixed
     */
    public function &__get( $property )
    {
        $value = null;
        $property = $this->get_alias_property( $property );

        if ( preg_match( '/field_/', $property )
            && array_key_exists( preg_replace( '/field_/', '', $property ), $this->attributes )
        ) {
            return $this->attributes[preg_replace( '/field_/', '', $property )];
        }

        if ( preg_match( '/func_/', $property ) ) {

            $function_name = preg_replace( '/func_/', '', $property );

            $value = $this->$function_name();
        }
        return $value;
    }

    /**
     * Setter function.
     * @since 1.0.0
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return object
     */
    public function __set( $property, $value )
    {
        $property = $this->get_alias_property( $property );

        if ( preg_match( '/field_/', $property ) ) {

            $this->attributes[preg_replace( '/field_/', '', $property )] = $value;

        }
    }

    /**
     * Returns object converted to array.
     * @since 1.0.0
     *
     * @param array.
     */
    public function to_array()
    {
        $output = array();

        // Attributes
        foreach ($this->attributes as $property => $value) {
            $output[$this->get_alias('field_' . $property)] = $value;
        }

        // Functions
        foreach ($this->aliases as $alias => $property) {
            if ( preg_match( '/func_/', $property ) ) {
                $function_name = preg_replace( '/func_/', '', $property );
                $output[$alias] = $this->$function_name();
            }
        }

        // Hidden
        foreach ( $this->hidden as $key ) {
            unset( $output[$key] );
        }

        return $output;
    }

    /**
     * Returns object converted to array.
     * @since 1.0.0
     *
     * @param array.
     */
    public function __toArray()
    {
        return $this->to_array();
    }
}