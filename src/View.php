<?php

namespace WPMVC\MVC;

/**
 * View class.
 * Extends templating functionality to apply a mini MVC engine.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.0.3
 */
class View
{
    /**
     * Path to where controllers are.
     * @since 1.0.0
     * @var string
     */
    protected $views_path;

    /**
     * Default engine constructor.
     * @since 1.0.0
     *
     * @param string $controllers_path
     * @param string $namespace
     */
    public function __construct( $views_path )
    {
        $this->views_path = $views_path;
    }

    /**
     * Returns view with the parameters passed by.
     * @since 1.0.0
     * @since 2.0.3 Support for child themes and re-mapped custom themes to 'assets' folder.
     *
     * @param string $view   Name and location of the view within "theme/views" path.
     * @param array  $params View parameters passed by.
     *
     * @return string
     */
    public function get( $view, $params = array() )
    {
        $template = preg_replace( '/\./', '/', $view );
        $theme_path =  get_stylesheet_directory() . '/assets/views/' . $template . '.php';
        $plugin_path = $this->views_path . $template . '.php';
        $path = is_readable( $theme_path )
            ? $theme_path
            : ( is_readable( $plugin_path )
                ? $plugin_path
                : null
            );
        if ( ! empty( $path ) ) {
            extract( $params );
            ob_start();
            include( $path );
            return ob_get_clean();
        } else {
            return;
        }
    }

    /**
     * Displays view with the parameters passed by.
     * @since 1.0.0
     *
     * @param string $view   Name and location of the view within "theme/views" path.
     * @param array  $params View parameters passed by.
     */
    public function show( $view, $params = array() )
    {
        echo $this->get( $view, $params );
    }

    /**
     * Displays content as JSON.
     * @since 1.0.1
     *
     * @param mixed $content Content to display as JSON.
     * @param array $headers JSON override headers.
     */
    public function json( $content, $headers = [] )
    {
        if ( empty( $headers ) )
            $headers = ['Content-Type: application/json'];
        foreach ( $headers as $header ) {
            header( $header );
        }
        if ( is_object( $content )
            && method_exists($content, 'to_json')
        ) {
            echo $content->to_json();
        } else {
            echo json_encode( is_array( $content ) ? $content : (array)$content );
        }
        die;
    }
}