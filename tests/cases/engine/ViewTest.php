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
     * Tests view located in theme folder.
     */
    public function testThemeView()
    {
        $this->assertViewOutput('theme', 'test theme view');
    }

    /**
     * Tests view parameters.
     */
    public function testViewParameters()
    {
        $this->assertViewOutput('params', '21', ['arg1' => 2, 'arg2' => 1]);
    }
}