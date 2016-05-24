<?php

namespace WPMVC\MVC\Traits;

/**
 * Generic cast to array trait.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
trait ArrayCastTrait
{
    /**
     * Returns object converted to array.
     * @since 1.0.0
     *
     * @param array.
     */
    public function __toArray()
    {
        $output = array();

        // Attributes
        foreach ($this->attributes as $property => $value) {
            $output[$this->get_alias($property)] = $value;
        }

        // Meta
        foreach ($this->meta as $key => $value) {
            $alias = $this->get_alias('meta_' . $key);
            if ( $alias !=  'meta_' . $key) {
                $output[$alias] = $value;
            }
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
}