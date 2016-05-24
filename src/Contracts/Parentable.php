<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for object who have parents.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
interface Parentable
{
	/**
	 * Returns an array collection of the implemented class based on parent ID.
	 * Returns children from parent.
     * @since 1.0.0
	 *
	 * @param int $id Parent post ID.
	 *
	 * @return array
	 */
	public static function from( $id );
}