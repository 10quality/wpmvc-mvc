<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for findable objects.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
interface Findable
{
	/**
	 * Finds record based on an ID.
     * @since 1.0.0
     *
	 * @param mixed $id Record ID.
	 */
	public static function find( $id );
}