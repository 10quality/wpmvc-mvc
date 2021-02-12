<?php
/**
 * Tests MVC Model Controller.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.12
 */
class SubfolderTest extends MVCTestCase
{
    /**
     * Tests model controller method.
     * @since 2.1.13
     * @group controllers
     * @dataProvider providerControllerSubfolder
     *
     * @param string $handler
     * @param mixed  $return
     */
    public function testControllerSubfolder($handler, $return)
    {
        // Assert
        $this->assertControllerAction($handler, $return);
    }
    /**
     * Returns dataset for test.
     * @since 2.1.13
     *
     * @see self::testControllerSubfolder
     *
     * @return array
     */
    public function providerControllerSubfolder()
    {
        return [
            ['Sub\TestController@sub', true],
            ['Sub\TestController@string', 'This is a test sub'],
        ];
    }
}