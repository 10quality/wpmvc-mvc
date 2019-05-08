<?php

namespace WPMVC\MVC\Traits;

use WPMVC\MVC\Collection as Collection;

/**
 * Trait related to all find functionality of a model.
 *
 * @author Cami M
 * @copyright 10 Quality Studio <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.5
 */
trait FindTermTrait
{
    /**
     * Finds record based on an ID.
     * @since 2.1.5
     *
     * @param mixed  $id       Record ID.
     * @param string $taxonomy Taxonomy.
     */
    public static function find( $id = 0, $taxonomy = null )
    {
        return new self( $taxonomy, $id );
    }

    /**
     * Finds record based on a slug.
     * @since 2.1.5
     *
     * @param string $slug     Term slug.
     * @param string $taxonomy Taxonomy.
     */
    public static function find_by_slug( $slug = 0, $taxonomy = null )
    {
        $model = new self( $taxonomy, 0 );
        $model->load_by_slug( $slug );
        return $model;
    }

    /**
     * Returns all terms found in the db.
     * @since 1.0.0
     * 
     * @param string $taxonomy Taxonomy to search for.
     * 
     * @return \WPMVC\MVC\Collection
     */
    public static function all( $taxonomy = null, $hide_empty = false )
    {
        $output = new Collection;
        if ( empty( $taxonomy ) )
            $taxonomy = $this->model_taxonomy;
        $terms = get_terms( $taxonomy, ['hide_empty' => $hide_empty] );
        foreach ( $terms as $term ) {
            $obj = new self();
            $output[] = $obj->from_term( $term );
        }
        return $output;
    }
}