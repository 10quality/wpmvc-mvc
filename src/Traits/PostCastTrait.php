<?php

namespace WPMVC\MVC\Traits;

/**
 * Trait related to all casting functionality of a model.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.1
 */
trait PostCastTrait
{
	/**
	 * Constructs object based on passed object.
	 * Should be an array of attributes or a WP_Post.
	 * @since 1.0.0
	 *
	 * @param mixed $object Array of attributes or a WP_Post.
	 */
	public function from_post( $object )
	{
		if ( is_array( $object ) ) {

			$this->attributes = $object;

		} else if ( is_a( $object, 'WP_Post' ) ) {

			$this->attributes = $object->to_array();

		}

		if ( ! empty( $this->attributes ) ) {

			$this->load_meta();
			
		}

		return $this;
	}

	/**
	 * Cast object into a WP_Post.
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public function to_post()
	{
		return \WP_Post::get_instance( $this->attributes['ID'] );
	}
}