<?php

namespace WPMVC\MVC\Models;

use WPMVC\MVC\Contracts\Modelable;
use WPMVC\MVC\Contracts\Findable;
use WPMVC\MVC\Contracts\Metable;
use WPMVC\MVC\Contracts\Parentable;
use WPMVC\MVC\Contracts\PostCastable;
use WPMVC\MVC\Contracts\Arrayable;
use WPMVC\MVC\Contracts\JSONable;
use WPMVC\MVC\Contracts\Stringable;
use WPMVC\MVC\Traits\MetaTrait;
use WPMVC\MVC\Traits\PostCastTrait;
use WPMVC\MVC\Traits\CastTrait;
use WPMVC\MVC\Traits\AliasTrait;
use WPMVC\MVC\Traits\SetterTrait;
use WPMVC\MVC\Traits\ArrayCastTrait;

/**
 * Abstract Post Model Class.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
abstract class PostModel implements Modelable, Findable, Metable, Parentable, PostCastable, Arrayable, JSONable, Stringable
{
	use MetaTrait, PostCastTrait, CastTrait, AliasTrait, SetterTrait, ArrayCastTrait;

	/**
	 * Post type.
	 * @var string
	 */
	protected $type = 'post';

	/**
	 * Default post status.
	 * @var string
	 */
	protected $status = 'draft';

	/**
	 * Posts are moved to trash when on soft delete.
	 * @var bool
	 */
	protected $forceDelete = false;

	/**
	 * Attributes in model.
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Attributes and aliases hidden from print.
	 * @var array
	 */
	protected $hidden = array();

	/**
	 * Default constructor.
	 */
	public function __construct( $id = 0 )
	{
		if ( ! empty( $id )  )
			$this->load($id);
	}

	/**
	 * Loads model from db.
	 *
	 * @param mixed $id Rercord ID.
	 */
	public function load( $id )
	{
		$this->attributes = \get_post( $id, ARRAY_A );

		$this->load_meta();
	}

	/**
	 * Saves current model in the db.
	 *
	 * @return mixed.
	 */
	public function save()
	{
		if ( ! $this->is_loaded() ) return false;

		$this->fill_defaults();

		$error = null;

		$id = wp_insert_post( $this->attributes, $error );

		if ( ! empty( $id ) ) {

			$this->attributes['ID'] = $id;

			$this->save_meta_all();
		}

		return $error === false ? true : $error;
	}

	/**
	 * Deletes current model in the db.
	 *
	 * @return mixed.
	 */
	public function delete()
	{
		if ( ! $this->is_loaded() ) return false;

		$error = wp_delete_post( $this->attributes['ID'], $this->forceDelete );

		return $error !== false;
	}

	/**
	 * Returns flag indicating if object is loaded or not.
	 *
	 * @return bool
	 */
	public function is_loaded()
	{
		return !empty( $this->attributes );
	}

	/**
	 * Getter function.
	 *
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get( $property )
	{
		$property = $this->get_alias_property( $property );

		if ( preg_match( '/meta_/', $property ) ) {

			return $this->get_meta( preg_replace( '/meta_/', '', $property ) );

		}

		if ( preg_match( '/func_/', $property ) ) {

			$function_name = preg_replace( '/func_/', '', $property );

			return $this->$function_name();
		}

		if ( array_key_exists( $property, $this->attributes ) ) {

			return $this->attributes[$property];

		} else {

			switch ($property) {

				case 'type':
				case 'status':
					return $this->$property;

				case 'post_content_filtered':
					$content = \apply_filters( 'the_content', $this->attributes[$property] );
					$content = str_replace( ']]>', ']]&gt;', $content );
					return $content;
			}

		}

		return null;
	}

	/**
	 * Fills default when about to create object
	 */
	private function fill_defaults()
	{
		if ( ! array_key_exists('ID', $this->attributes) ) {

			$this->attributes['post_type'] = $this->type;

			$this->attributes['post_status'] = $this->status;
		}
	}
}
