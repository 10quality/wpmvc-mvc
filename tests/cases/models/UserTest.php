<?php
/**
 * Tests MVC user model.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.7
 */
class UserTest extends MVCTestCase
{
    /**
     * Tests model construction.
     * @group models
     */
    public function testConstruct()
    {
        $user = new User;
        $this->assertEquals([], $user->to_array());
    }
    /**
     * Tests model find.
     * @group models
     */
    public function testFind()
    {
        $user = User::find(404);
        $this->assertEquals(404, $user->ID);
        $this->assertEquals('John', $user->first_name);
    }
    /**
     * Tests model aliases.
     * @group models
     */
    public function testAliases()
    {
        $user = User::find(404);
        $user->setAliases([
            'firstname' => 'first_name',
            'fullname'  => 'func_fullname',
        ]);
        $this->assertEquals('John', $user->firstname);
        $this->assertEquals('John Doe', $user->fullname);
        $user->firstname = 'test';
        $this->assertEquals('test', $user->firstname);
    }
    /**
     * Tests model meta.
     * @group models
     */
    public function testMeta()
    {
        $user = User::find(404);
        $user->setAliases([
            'views'  => 'meta_views',
        ]);
        $this->assertNull($user->views);
        $user->views = 99;
        $this->assertEquals($user->views, 99);
        $this->assertTrue($user->has_meta('views'));
        $this->assertFalse($user->has_meta('viewed'));
        $this->assertTrue($user->save());
    }
    /**
     * Tests model casting to array.
     * @group models
     */
    public function testCastingArray()
    {
        $user = User::find(404);
        $user->setHidden([
            'caps',
            'cap_key',
            'roles',
            'allcaps',
            'first_name',
            'last_name',
            'user_login',
            'user_email',
        ]);
        $this->assertEquals(['ID' => 404], $user->to_array());
    }
    /**
     * Tests model casting to string / json.
     * @group models
     */
    public function testCastingString()
    {
        $user = User::find(404);
        $user->setHidden([
            'caps',
            'cap_key',
            'roles',
            'allcaps',
            'first_name',
            'last_name',
            'user_email',
        ]);
        $this->assertEquals('{"ID":404,"user_login":"admin"}', (string)$user);
    }
}