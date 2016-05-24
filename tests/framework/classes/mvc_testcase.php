<?php

use WPMVC\MVC\Engine;

/**
 * Custom engine test case.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
abstract class MVCTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * MVC engine.
     * @var object
     * @since 1.0.0
     */
    protected $engine;

    /**
     * Constructs a test case with the given name.
     * @since 1.0
     *
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->engine = new Engine(
            __DIR__ . '/../plugin/views/',
            __DIR__ . '/../plugin/controllers/',
            'WPMVC\Testing'
        );
    }
}