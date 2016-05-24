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
     * @since 1.0.0
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

    /**
     * Asserts the execution of a controller output.
     * @since 1.0.0
     *
     * @param string $call    Controller call.
     * @param string $output  Controller output.
     * @param array  $params  Controller parameters.
     * @param string $message PHPUNIT message.
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function assertControllerCall($call, $output = '', $params = [], $message = 'Failed asserting controller call output.')
    {
        ob_start();
        $this->engine->call($call, $params);
        self::assertThat(
            ob_get_clean() == $output,
            self::isTrue(),
            $message
        );
    }

    /**
     * Asserts the execution of a controller output.
     * @since 1.0.0
     *
     * @param string $call    Controller call.
     * @param string $output  Controller output.
     * @param array  $params  Controller parameters.
     * @param string $message PHPUNIT message.
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function assertControllerAction($call, $output = '', $params = [], $message = 'Failed asserting controller action return.')
    {
        self::assertThat(
            $this->engine->action($call, $params) == $output,
            self::isTrue(),
            $message
        );
    }

    /**
     * Asserts the execution of a controller output.
     * @since 1.0.0
     *
     * @param string $call    Controller call.
     * @param string $output  Controller output.
     * @param array  $params  Controller parameters.
     * @param string $message PHPUNIT message.
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function assertViewOutput($view, $output = '', $params = [], $message = 'Failed asserting view output.')
    {
        ob_start();
        $this->engine->view->show($view, $params);
        self::assertThat(
            ob_get_clean() == $output,
            self::isTrue(),
            $message
        );
    }
}