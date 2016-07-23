<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for sortable collections.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
interface Sortable
{
    /**
     * Sorts results by specific field and direction.
     * @since 1.0.0
     *
     * @param string $attribute Attribute to sort by.
     * @param string $sort_flag Sort direction.
     *
     * @return this for chaining
     */
    public function sort_by( $attribute, $sort_flag = SORT_REGULAR );
}