<?php

namespace WPMVC\MVC\Traits;

/**
 * Generic casting trait.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
trait CastTrait
{
    /**
     * Returns object converted to array for serialization.
     * @since 1.0.0
     *
     * @param array.
     */
    public function __sleep()
    {
        return $this->to_array();
    }

    /**
     * Returns json string.
     *
     * @param string
     */
    public function to_json()
    {
        return json_encode( $this->to_array() );
    }

    /**
     * Returns string.
     *
     * @param string
     */
    public function __toString()
    {
        return $this->to_json();
    }
}