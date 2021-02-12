<?php

/**
 * MVC global functions.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.11.3
 */

if ( !function_exists( 'wpmvc_array_value' ) ) {
    /**
     * Returns any value suited for array casting.
     * @since 2.1.11.3
     *
     * @param mixed $value
     *
     * @return mixed
     */
    function wpmvc_array_value( $value )
    {
        // Recursive casting for objects and arrays
        if ( is_object( $value ) ) {
            if ( method_exists( $value, 'to_array' ) ) {
                return wpmvc_array_value( $value->to_array() );
            } elseif ( method_exists( $value, 'toArray' ) ) {
                return wpmvc_array_value( $value->toArray() );
            } elseif ( method_exists( $value, 'array' ) ) {
                return wpmvc_array_value( $value->array() );
            } else {
                return wpmvc_array_value( (array)$value );
            }
        } elseif ( is_array( $value ) ) {
            foreach ( $value as $key => $val ) {
                $value[$key] = wpmvc_array_value( $val );
            }
        }
        // Return of simple values
        return $value;
    }
}