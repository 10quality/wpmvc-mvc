<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for object that can be casted to or from WP_Post.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
interface PostCastable
{
	/**
	 * Constructs object based on passed object.
	 * Should be an array of attributes or a WP_Post.
     * @since 1.0.0
	 *
	 * @param mixed $object Array of attributes or a WP_Post.
	 */
	public function from_post( $object );

	/**
	 * Cast object into a WP_Post.
     * @since 1.0.0
	 *
	 * @return object
	 */
	public function to_post();
}