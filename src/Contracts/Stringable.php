<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for objects that can cast to string.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
interface Stringable
{
	/**
	 * Returns string.
     * @since 1.0.0
	 *
	 * @param string
	 */
	public function __toString();
}