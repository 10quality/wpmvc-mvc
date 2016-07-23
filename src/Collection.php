<?php

namespace WPMVC\MVC;

use ArrayObject;
use WPMVC\MVC\Contracts\Sortable;
use WPMVC\MVC\Contracts\JSONable;
use WPMVC\MVC\Contracts\Stringable;

/**
 * Holds a collection of model results.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.1
 */
class Collection extends ArrayObject implements Sortable, JSONable, Stringable
{
    /**
     * Sorts results by specific field and direction.
     * @since 1.0.0
     *
     * @param string $attribute Attribute to sort by.
     * @param string $sort_flag Sort direction.
     *
     * @return this for chaining
     */
    public function sort_by( $attribute, $sort_flag = SORT_REGULAR )
    {
        $values = array();
        for ( $i = count( $this ) -1; $i >= 0; --$i ) {
            $values[] = $this[$i]->$attribute;
        }
        $values = array_unique($values);
        sort( $values, $sort_flag );
        $new = new self();
        foreach ( $values as $value ) {
            for ( $i = count( $this ) -1; $i >= 0; --$i ) {
                if ( $value == $this[$i]->$attribute ) {
                    $new[] = $this[$i];
                }
            }
        }
        return $new;
    }

    /**
     * Groups collection by attribute name.
     * @since 1.0.0
     *
     * @param string $attribute Attribute to group by.
     *
     * @return this for chaining
     */
    public function group_by( $attribute )
    {
        $new = new self();
        for ( $i = 0; $i < count( $this ); ++$i ) {
            $key = (string)$this[$i]->$attribute; 
            if ( ! isset( $new[$key] ) )
                $new[$key] = new self();
            $new[$key][] = $this[$i];
        }
        return $new;
    }

    /**
     * Returns json string.
     * @since 1.0.0
     * @since 1.0.1 Checks on inner objects for better conversion.
     *
     * @param string
     */
    public function to_json()
    {
        $output = [];
        // Check on each object
        foreach ( $this as $key => $value ) {
            $output[$key] = is_object( $value ) && property_exists( $value, 'to_array' )
                ? $value->to_array()
                : is_array( $value ) ? $value : (array)$value;
        }
        return json_encode( $output );
    }

    /**
     * Returns string.
     * @since 1.0.0
     *
     * @param string
     */
    public function __toString()
    {
        return $this->to_json();
    }
}