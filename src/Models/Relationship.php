<?php

namespace WPMVC\MVC\Models;

/**
 * Relationship class.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.0.4
 */
class Relationship
{
    /**
     * Has one relationship constant.
     * @since 2.0.4
     *
     * @var string
     */
    const HAS_ONE = 'has_one';
    /**
     * Belongs to parent relationship constant.
     * @since 2.0.4
     *
     * @var string
     */
    const BELONGS_TO = 'belongs_to';
    /**
     * Has many relationship constant.
     * @since 2.0.4
     *
     * @var string
     */
    const HAS_MANY = 'has_many';
    /**
     * Parent class reference.
     * @since 2.0.4
     *
     * @var  Model|Object
     */
    public $parent;
    /**
     * Holds the loaded object found in relationship.
     * @since 2.0.4
     *
     * @var Object
     */
    public $object;
    /**
     * Relationship model class name.
     * @since 2.0.4
     *
     * @var string
     */
    public $class;
    /**
     * Property in class that is mapped to the indefier of the relationship model.
     * @since 2.0.4
     *
     * @var string
     */
    public $property;
    /**
     * Method in relationship model used to load it.
     * @since 2.0.4
     *
     * @var string
     */
    public $method;
    /**
     * Global function used to load relationship model.
     * @since 2.0.4
     *
     * @var string
     */
    public $function;
    /**
     * Relationship type.
     * @since 2.0.4
     *
     * @var string
     */
    public $type;
    /**
     * Sets a relationship within the model.
     * @since 2.0.4
     *
     * @param object|Model &$parent  Parent class reference.
     * @param string|mixed $class    Relationship model class name.
     * @param string       $property Property in class that is mapped to the indefier of the relationship model.
     * @param string       $method   Method in relationship model used to load it.
     * @param string       $type     Relationship type.
     */
    public function __construct(&$parent, $type, $class, $property, $method, $function = null)
    {
        $this->parent = $parent;
        $this->class = $class;
        $this->property = $property;
        $this->method = $method;
        $this->type = $type;
        $this->function = $function;
    }
}
