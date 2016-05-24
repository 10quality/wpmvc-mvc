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
        $value = null;
        $property = $this->get_alias_property( $property );

        if ( preg_match( '/meta_/', $property ) ) {
            $value = $this->get_meta( preg_replace( '/meta_/', '', $property ) );
        }

        if ( preg_match( '/func_/', $property ) ) {
            $function_name = preg_replace( '/func_/', '', $property );
            $value = $this->$function_name();
        }

        if ( array_key_exists( $property, $this->attributes ) ) {
            $value = $this->attributes[$property];
        }

        if ( is_object( $this->data ) && property_exists( $this->data, $property ) ) {
            $value = $this->data->$property;
        }

        return $value;
    }
}