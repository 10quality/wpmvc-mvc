<?php

namespace WPMVC\MVC\Traits;

use WPMVC\MVC\Collection;
use WPMVC\MVC\Models\Relationship;
use WPMVC\MVC\Models\Common\Attachment;

/**
 * Trait used to create relationships with models.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.7
 */
trait RelationshipTrait
{
    /**
     * Relationships data.
     * @since 2.0.4
     * @var array
     */
    protected $rel = array(
        'has_one'       => array(),
        'has_many'      => array(),
        'belongs_to'    => array(),
    );

    /**
     * Returns object or objects associated with relationship.
     * @since 2.0.4
     *
     * @param string $method Relationship method name.
     *
     * @return mixed|Object|Collection
     */
    private function get_relationship( $method )
    {
        // Get relationship
        $rel = call_user_func_array( array( &$this, $method ), array() );
        // Return on null
        if ( $rel === null || !is_object( $rel ) || !$rel instanceof Relationship )
            return;
        $property = $rel->property;
        // Return on null
        if ( $this->$property === null )
            return;
        // Load object if needed
        if ( $rel->object === null ) {
            switch ( $rel->type ) {
                case Relationship::HAS_ONE:
                case Relationship::BELONGS_TO:
                    if ($rel->function && $rel->method === null) {
                        $rel->object = call_user_func_array( $rel->function, array( $this->$property ) );
                    } else {
                        $rel->object = $rel->function
                            ? call_user_func_array(
                                $rel->class . '::' . $rel->method,
                                array( call_user_func_array( $rel->function, array( $this->$property ) ) )
                            )
                            : call_user_func_array(
                                $rel->class . '::' . $rel->method,
                                array( $this->$property )
                            );
                    }
                    // Update relationship
                    $this->rel[$rel->type][$rel->class]->object = $rel->object;
                    break;
                case Relationship::HAS_MANY:
                    $rel->object = [];
                    foreach ( $this->$property as $id ) {
                        if ($rel->function && $rel->method === null) {
                            $rel->object[] = call_user_func_array( $rel->function, array( $id ) );
                        } else {
                            $rel->object[] = $rel->function
                                ? call_user_func_array(
                                    $rel->class . '::' . $rel->method,
                                    array( call_user_func_array( $rel->function, array( $id ) ) )
                                )
                                : call_user_func_array(
                                    $rel->class . '::' . $rel->method,
                                    array( $id )
                                );
                        }
                    }
                    // Update relationship
                    $this->rel[$rel->type][$rel->class]->object = $rel->object;
                    break;
            }
        }
        return $rel->object;
    }
    /**
     * Returns relationship class.
     * 1-to-1 Relationship.
     * Owner relationship.
     * @since 2.0.4
     *
     * @param string|mixed $class    Relationship model class name.
     * @param string       $property Property in class that is mapped to the indentifier of the relationship model.
     * @param string       $method   Method in relationship model used to load it.
     * @param string       $function Global function used to load relationship model.
     *
     * @return object|Relationship
     */
    protected function has_one( $class, $property, $method = 'find', $function = null )
    {
        if ( !isset( $this->rel[Relationship::HAS_ONE][$class] ) )
            $this->rel[Relationship::HAS_ONE][$class] = new Relationship(
                $this,
                Relationship::HAS_ONE,
                $class,
                $property,
                $method,
                $function
            );
        return $this->rel[Relationship::HAS_ONE][$class];
    }
    /**
     * Returns relationship class.
     * 1-to-1 Relationship.
     * Owner relationship.
     * @since 2.0.4
     *
     * @param string|mixed $class    Relationship model class name.
     * @param string       $property Property in class that is mapped to the indentifier of the relationship model.
     * @param string       $method   Method in relationship model used to load it.
     * @param string       $function Global function used to load relationship model.
     *
     * @return object|Relationship
     */
    protected function belongs_to( $class, $property, $method = 'find', $function = null )
    {
        if ( !isset( $this->rel[Relationship::BELONGS_TO][$class] ) )
            $this->rel[Relationship::BELONGS_TO][$class] = new Relationship(
                $this,
                Relationship::BELONGS_TO,
                $class,
                $property,
                $method,
                $function
            );
        return $this->rel[Relationship::BELONGS_TO][$class];
    }
    /**
     * Returns relationship class.
     * 1-to-N Relationship.
     * Owner relationship.
     * @since 2.0.4
     *
     * @param string|mixed $class    Relationship model class name.
     * @param string       $property Property in class that is mapped to the indentifier of the relationship model.
     * @param string       $method   Method in relationship model used to load it.
     * @param string       $function Global function used to load relationship model.
     *
     * @return object|Relationship
     */
    protected function has_many( $class, $property, $method = 'find', $function = null )
    {
        if ( !isset( $this->rel[Relationship::HAS_MANY][$class] ) )
            $this->rel[Relationship::HAS_MANY][$class] = new Relationship(
                $this,
                Relationship::HAS_MANY,
                $class,
                $property,
                $method,
                $function
            );
        return $this->rel[Relationship::HAS_MANY][$class];
    }
    /**
     * Returns relationship class.
     * Standard WordPress featured attachment relationship.
     * @since 2.0.4
     * @since 2.1.3 Remove `::class` to support php 5.4.
     *
     * @return object|Relationship
     */
    protected function has_featured( $class = null )
    {
        if ( $class === null )
            $class = '\WPMVC\MVC\Models\Common\Attachment';
        if ( !isset( $this->rel[Relationship::HAS_ONE][$class] ) )
            $this->rel[Relationship::HAS_ONE][$class] = new Relationship(
                $this,
                Relationship::HAS_ONE,
                $class,
                'ID',
                'find',
                'get_post_thumbnail_id'
            );
        return $this->rel[Relationship::HAS_ONE][$class];
    }
}