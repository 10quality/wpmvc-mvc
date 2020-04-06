<?php
/**
 * Tests MVC Controllers.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
class ControllerTest extends MVCTestCase
{
    /**
     * Tests parameters.
     * @group controllers
     */
    public function testParameters()
    {
        $param = 1;
        $this->assertControllerAction('TestController@param', 1, $param);
    }
    /**
     * Tests call output (view handling).
     * @group controllers
     */
    public function testCallOutput()
    {
        $this->assertControllerCall('TestController@view', 'test', 'plugin');
    }
    /**
     * Tests call output.
     * @group controllers
     */
    public function testUser()
    {
        $this->assertControllerAction('TestController@user_id', 404);
    }
}