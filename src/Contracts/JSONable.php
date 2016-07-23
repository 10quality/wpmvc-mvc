<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for objects that can cast to json.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
interface JSONable
{
    /**
     * Returns json string.
     * @since 1.0.0
     *
     * @param string
     */
    public function to_json();
}