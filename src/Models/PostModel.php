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
 * @version 1.0.2
 */
abstract class PostModel implements Modelable, Findable, Metable, Parentable, PostCastable, Arrayable, JSONable, Stringable
{
    use MetaTrait, PostCastTrait, CastTrait, AliasTrait, SetterTrait, ArrayCastTrait;

    /**
     * Post type.
     * @since 1.0.0
     * @var string
     */
    protected $type = 'post';

    /**
     * Default post status.
     * @since 1.0.0
     * @var string
     */
    protected $status = 'draft';

    /**
     * Posts are moved to trash when on soft delete.
     * @since 1.0.0
     * @var bool
     */
    protected $forceDelete = false;

    /**
     * Attributes in model.
     * @since 1.0.0
     * @var array
     */
    protected $attributes = [];

    /**
     * Attributes and aliases hidden from print.
     * @since 1.0.0
     * @var array
     */
    protected $hidden = [];

    /**
     * Registry options for during registration.
     * @since 1.0.1
     * @var array
     */
    protected $registry = [
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
    ];

    /**
     * Labels for during registration.
     * @since 1.0.1
     * @var array
     */
    protected $registry_labels = [];

    /**
     * Wordpress support for during registration.
     * @since 1.0.1
     * @var array
     */
    protected $registry_supports = [
        'title',
        'editor',
        'author',
        'thumbnail',
        'excerpt',
        'comments',
    ];

    /**
     * Rewrite rules for during registration.
     * @since 1.0.1
     * @var array
     */
    protected $registry_rewrite = [];

    /**
     * Flag that indicates if model should generate metabox for aliases upon registration.
     * @since 1.0.2
     * @var bool
     */
    protected $registry_metabox = true;

    /**
     * Name of the controller used for registration purposes.
     * @since 1.0.2
     * @var string
     */
    protected $registry_controller;

    /**
     * Taxonomies to register during autoregistration.
     * @since 1.0.2
     * @var array
     */
    protected $registry_taxonomies = [];

    /**
     * Default constructor.
     * @since 1.0.0
     */
    public function __construct( $id = 0 )
    {
        if ( ! empty( $id )  )
            $this->load($id);
    }

    /**
     * Loads model from db.
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     *
     * @return bool
     */
    public function is_loaded()
    {
        return !empty( $this->attributes );
    }

    /**
     * Getter function.
     * @since 1.0.0
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
                case 'meta':
                case 'aliases':
                case 'registry':
                case 'registry_taxonomies':
                case 'registry_controller':
                case 'registry_metabox':
                case 'registry_rewrite':
                case 'registry_supports':
                case 'registry_labels':
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
     * @since 1.0.0
     */
    private function fill_defaults()
    {
        if ( ! array_key_exists('ID', $this->attributes) ) {

            $this->attributes['post_type'] = $this->type;

            $this->attributes['post_status'] = $this->status;
        }
    }
}
