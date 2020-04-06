<?php
/**
 * Tests MVC Views.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.7
 */
class ViewTest extends MVCTestCase
{
    /**
     * Tests view located in theme folder.
     * @group views
     */
    public function testThemeView()
    {
        $this->assertViewOutput('theme', 'test theme view');
    }
    /**
     * Tests view parameters.
     * @group views
     */
    public function testViewParameters()
    {
        $this->assertViewOutput('params', '21', ['arg1' => 2, 'arg2' => 1]);
    }
}