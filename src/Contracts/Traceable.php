<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for objects that can cast to string.
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10 Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.11
 */
interface Traceable
{
    /**
     * Returns flag indicating if model has a trace in the database (an ID).
     * @since 2.1.11
     *
     * @param bool
     */
    public function has_trace();
}