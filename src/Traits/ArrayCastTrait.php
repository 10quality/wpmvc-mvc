<?php

namespace WPMVC\MVC\Traits;

/**
 * Generic cast to array trait.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.0.4
 */
trait ArrayCastTrait
{
    /**
     * Returns object converted to array.
     * @since 1.0.1
     * @since 2.0.4 Improve casting tree.
     *
     * @param array.
     */
    public function to_array()
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
                $output[$alias] = wpmvc_array_value($value);
            }
        }
        unset($value);
        unset($alias);
        // Functions
        foreach ($this->aliases as $alias => $property) {
            if ( preg_match( '/func_/', $property ) ) {
                $function_name = preg_replace( '/func_/', '', $property );
                $output[$alias] = wpmvc_array_value($this->$function_name());
            }
        }
        unset($function_name);
        // Relationships
        foreach ( get_class_methods( $this ) as $method ) {
            if ( ! preg_match( '/_meta|to_|\_\_|load|save|delete|from|find|alias|get|set|has_|belongs/', $method )
                && $this->$method !== null
            ) {
                $output[$method] = wpmvc_array_value($this->$method);
            }
        }
        unset($method);
        // Hidden
        foreach ( $this->hidden as $key ) {
            unset( $output[$key] );
        }
        unset($key);
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