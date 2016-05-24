<?php

namespace WPMVC\MVC\Contracts;

/**
 * Interface contract for objects with Meta.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
interface Metable
{
	/**
	 * Loads meta values into objet.
     * @since 1.0.0
	 */
	public function load_meta();

	/**
	 * Returns flag indicating if object has meta key.
     * @since 1.0.0
	 *
	 * @param string $key Key.
	 *
	 * @return bool
	 */
	public function has_meta( $key );

	/**
	 * Gets value from meta.
     * @since 1.0.0
	 *
	 * @param string $key Key.
	 *
	 * @return mixed.
	 */
	public function get_meta( $key );

	/**
	 * Sets meta value.
     * @since 1.0.0
	 *
	 * @param string $key   Key.
	 * @param mixed  $value Value.
	 */
	public function set_meta( $key, $value );

	/**
	 * Deletes meta.
     * @since 1.0.0
	 *
	 * @param string $key Key.
	 */
	public function delete_meta( $key );

	/**
	 * Either adds or updates a meta.
     * @since 1.0.0
	 *
	 * @param string $key   Key.
	 * @param mixed  $value Value.
	 */
	public function save_meta( $key, $value );

	/**
	 * Saves all meta values.
     * @since 1.0.0
	 */
	public function save_meta_all();
}