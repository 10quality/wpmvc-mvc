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
 * @version 2.1.0
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
     * Alternate relative path (for themes) in which views can be located.
     * @since 2.0.0
     * @var string
     */
    protected $alt_relative_path = '/assets/views/';
    /**
     * Default engine constructor.
     * @since 1.0.0
     * @since 2.1.0 Added alt_relative_path
     *
     * @param string $views_path        Primary path in which views are located.
     * @param string $alt_relative_path Alternate relative path (for themes) in which views can be located.
     */
    public function __construct( $views_path, $alt_relative_path = null )
    {
        $this->views_path = $views_path;
        if ( $alt_relative_path !== null && is_string( $alt_relative_path ) )
            $this->alt_relative_path = $alt_relative_path;
    }
    /**
     * Returns view with the parameters passed by.
     * @since 1.0.0
     * @since 2.0.3 Support for child themes and re-mapped custom themes to 'assets' folder.
     * @since 2.1.0 Supports custom alternative path on themes.
     *
     * @param string $view   Name and location of the view within "theme/views" path.
     * @param array  $params View parameters passed by.
     *
     * @return string
     */
    public function get( $view, $params = array() )
    {
        $template = preg_replace( '/\./', '/', $view );
        $theme_path =  get_stylesheet_directory() . $this->alt_relative_path . $template . '.php';
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