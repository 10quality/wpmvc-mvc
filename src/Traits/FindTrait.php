<?php

namespace WPMVC\MVC\Traits;

use WPMVC\MVC\Collection as Collection;

/**
 * Trait related to all find functionality of a model.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.11.1
 */
trait FindTrait
{
    /**
     * Finds record based on an ID.
     * @since 1.0.0
     *
     * @param mixed $id Record ID.
     * 
     * @return object|null
     */
    public static function find( $id = 0 )
    {
        $model = new self( $id );
        if ( !method_exists( $model, 'has_trace' ) )
            return $model;
        return $model->has_trace() ? $model : null;
    }
    /**
     * Returns an array collection of the implemented class based on parent ID.
     * @since 1.0.0
     *
     * @param int $id Parent post ID.
     *
     * @return \WPMVC\MVC\Collection
     */
    public static function from( $id )
    {
        if ( empty( $id ) ) return;
        $output = new Collection;
        $reference = new self();
        $results = get_children( array(
            'post_parent' => $id,
            'post_type'   => $reference->type,
            'numberposts' => -1,
            'post_status' => $reference->status
        ), ARRAY_A );
        foreach ($results as $post_id => $post) {
            $model = new self();
            $output[] = $model->from_post( $post );
        }
        return $output;
    }
}