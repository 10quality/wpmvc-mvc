<?php

namespace WPMVC\MVC\Traits;

/**
 * Alias trait.
 * Provides with alias functionality.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
trait AliasTrait
{
    /**
     * Field aliases.
     * @var array
     */
    protected $aliases = array();
    /**
     * Returns property mapped to alias.
     * @since 1.0.0
     *
     * @param string $alias Alias.
     *
     * @return string
     */
    private function get_alias_property( $alias )
    {
        if ( array_key_exists( $alias, $this->aliases ) )
            return $this->aliases[$alias];
        return $alias;
    }
    /**
     * Returns alias name mapped to property.
     * @since 1.0.0
     *
     * @param string $property Property.
     *
     * @return string
     */
    private function get_alias( $property )
    {
        if ( in_array( $property, $this->aliases ) )
            return array_search( $property, $this->aliases );
        return $property;
    }
}