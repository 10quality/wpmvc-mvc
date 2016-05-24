<?php
/**
 * Tests MVC Views.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
class ViewTest extends MVCTestCase
{
    /**
     * Tests engine controller creation.
     */
    public function testThemeView()
    {
        $this->assertEquals($this->engine->view->get('theme'), 'test theme view');
    }
}