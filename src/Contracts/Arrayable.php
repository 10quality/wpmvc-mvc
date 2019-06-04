<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for objects that can cast to arrays.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.6
 */
interface Arrayable
{
    /**
     * Returns object converted to array.
     * @since 1.0.0
     *
     * @param array.
     */
    public function to_array();
    /**
     * Returns object converted to array.
     * @since 1.0.1
     *
     * @param array.
     */
    public function __toArray();
}