<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for Models.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
interface Modelable
{
	/**
	 * Loads model from db.
     * @since 1.0.0
	 */
	public function load( $id );

	/**
	 * Saves current model in the db.
     * @since 1.0.0
	 *
	 * @return mixed.
	 */
	public function save();
	
	/**
	 * Deletes current model in the db.
     * @since 1.0.0
	 *
	 * @return mixed.
	 */
	public function delete();
}