<?php

namespace WPMVC\MVC\Controllers;

use WPMVC\Request;
use WPMVC\MVC\Controller;

/**
 * Model reactive controller.
 * Controller base class.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
class ModelController extends Controller
{
    /**
     * Model to react to.
     * @since 1.0.0
     * @var string
     */
    protected $model = '';

    /**
     * Flag that indictes if model will save on autosave.
     * @since 1.0.0
     * @var bool
     */
    protected $autosave = false;

    /**
     * Model object.
     * @since 1.0.0
     * @var object
     */
    private $object;

    /**
     * Default constructor.
     * @since 1.0.0
     *
     * @param object $view View class object.
     */
    public function __construct( $view )
    {
        parent::__construct( $view );
        if (!empty($this->model)) {
            $model = '\\'.$this->model;
            $this->object = new $model();
        }
    }

    /**
     * Called on post save hook.
     * @since 1.0.0
     */
    public function _metabox( $post )
    {
        $model = call_user_method_array( 'find', $this->object, [$post->ID] );

        do_action( 'before_controller_metabox', $post->ID, $model );
        $this->on_metabox( $model );
        $model = apply_filters( 'metabox_model', $model );

        wp_nonce_field( '_wpmvc_post', '_wpmvc_nonce' );

        return $this->view->get( 'admin.metaboxes.'.$this->object->type.'.meta', [
            'model' => $model,
        ] );
    }

    /**
     * Called on post save hook.
     * @since 1.0.0
     */
    public function _save( $post_id )
    {
        $nonce = Request::input( '_wpmvc_nonce', '', true );

        if ( (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE && !$this->autosave )
            || empty($nonce) 
            || ! wp_verify_nonce( $nonce, '_wpmvc_post' ) 
        ) {
            return;
        }

        $model = call_user_method_array( 'find', $this->object, [$post->ID] );

        do_action( 'before_controller_save', $post_id, $model );

        foreach ( $model->aliases as $alias_key => $alias_value ) {
            if ( !preg_match( '/func\_/', $alias_value ) )
                $this->$alias_key = apply_filters(
                    'save_model_'.$mode->type.'_'.$alias_value,
                    Request::input( $alias_value )
                );
        }

        $this->on_save( $model );
        $model = apply_filters( 'save_model', $model );

        $model->save();
    }

    /**
     * Called controllers metabox method.
     * @since 1.0.0
     * @param object $model
     */
    public function on_metabox( &$model ) {}

    /**
     * Called controllers save method.
     * @since 1.0.0
     * @param object $model
     */
    public function on_save( &$model ) {}
}