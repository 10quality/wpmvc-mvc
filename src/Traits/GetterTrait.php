<?php

namespace WPMVC\MVC\Traits;

/**
 * Generic get trait.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
trait GetterTrait
{
    /**
     * Getter.
     * @since 1.0.0
     *
     * @param string $property Property name.
     *
     * @return mixed
     */
    public function &__get( $property )
    {
        $property = $this->get_alias_property( $property );

        if ( preg_match( '/meta_/', $property ) ) {
            return $this->get_meta( preg_replace( '/meta_/', '', $property ) );
        }

        if ( preg_match( '/func_/', $property ) ) {
            $function_name = preg_replace( '/func_/', '', $property );
            return $this->$function_name();
        }

        if ( array_key_exists( $property, $this->attributes ) ) {
            return $this->attributes[$property];
        }

        if ( is_object( $this->data ) && property_exists( $this->data, $property ) ) {
            return $this->data->$property;
        }

        return null;
    }
}