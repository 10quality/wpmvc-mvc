<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for Models.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
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