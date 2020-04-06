<?php
/**
 * Tests MVC engine.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
class EngineTest extends MVCTestCase
{
    /**
     * Tests engine construction.
     * @group engine
     */
    public function testConstruction()
    {
        $this->assertTrue(is_a($this->engine->view, 'WPMVC\MVC\View'));
    }
    /**
     * Tests engine controller creation.
     * @group engine
     */
    public function testController()
    {
        $this->assertTrue($this->engine->action('TestController@get'));
    }
    /**
     * Tests engine controller creation.
     * @group engine
     */
    public function testView()
    {
        $this->assertEquals($this->engine->view->get('plugin'), 'test');
    }
}