<?php

namespace WPMVC\MVC\Traits;

/**
 * Generic set trait.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.5
 */
trait SetterTrait
{
    /**
     * Setter.
     * @since 1.0.0
     * @since 2.1.5 Bug fixing.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return object
     */
    public function __set( $property, $value )
    {
        $property = $this->get_alias_property( $property );
        if ( preg_match( '/meta_/', $property ) ) {
            return $this->set_meta( preg_replace( '/meta_/', '', $property ), $value );
        } else {
            if ( ! is_array( $this->attributes ) )
                $this->attributes = array();
            $this->attributes[$property] = $value;
        }
    }
}