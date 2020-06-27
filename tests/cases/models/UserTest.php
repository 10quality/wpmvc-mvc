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
    /**
     * Tests loading model using a WP_User object.
     * @group models
     * @group user
     */
    public function testLoadFromWpUser()
    {
        // Prepare
        $wp_user = new WP_User(123);
        $user = new User;
        // Run
        $user->load_wp_user( $wp_user );
        // Assert
        $this->assertEquals(123, $user->ID);
        $this->assertEquals('email.123@test.test', $user->user_email);
    }
    /**
     * Tests return of WP_User.
     * @group models
     * @group user
     */
    public function testGetWpUser()
    {
        // Prepare
        $user = User::find(56);
        // Run
        $wp_user = $user->wp_user();
        // Assert
        $this->assertInstanceOf(WP_User::class, $wp_user);
        $this->assertEquals(56, $wp_user->ID);
        $this->assertEquals('email.56@test.test', $wp_user->data->user_email);
        $this->assertNotEmpty($wp_user->roles);
    }
    /**
     * Tests user insert.
     * @group models
     * @group user
     */
    public function testUserInsert()
    {
        // Prepare
        $user = new User;
        // Run
        $user->user_login = 'tester';
        $user->user_email = 'tester@test.test';
        $inserted = $user->save();
        // Assert
        global $data;
        $this->assertInternalType('bool', $inserted);
        $this->assertTrue($inserted);
        $this->assertEquals(707, $user->ID);
        $this->assertEquals('wp_insert_user', $data['trigger']);
        $this->assertEquals('tester', $data['user_login']);
        $this->assertEquals('tester@test.test', $data['user_email']);
    }
    /**
     * Tests user update.
     * @group models
     * @group user
     */
    public function testUserUpdate()
    {
        // Prepare
        $user = new User(777);
        $user->setAliases([
            'email' => 'user_email',
            'views' => 'meta_views',
            'calc'  => 'func_calc',
        ]);
        // Run
        $user->user_login = 'tester2';
        $user->email = 'tester2@test.test';
        $updated = $user->save();
        // Assert
        global $data;
        $this->assertInternalType('bool', $updated);
        $this->assertTrue($updated);
        $this->assertEquals(777, $user->ID);
        $this->assertEquals('wp_update_user', $data['trigger']);
        $this->assertArrayHasKey('user_email', $data);
        $this->assertArrayHasKey('ID', $data);
        $this->assertArrayNotHasKey('user_login', $data);
        $this->assertEquals(777, $data['ID']);
        $this->assertEquals('tester2@test.test', $data['user_email']);
    }
    /**
     * Tests user delete.
     * @group models
     * @group user
     */
    public function testUserDelete()
    {
        // Prepare
        $user = new User(777);
        // Run
        $deleted = $user->delete();
        // Assert
        global $data;
        $this->assertInternalType('bool', $deleted);
        $this->assertTrue($deleted);
        $this->assertEquals('wp_delete_user', $data['trigger']);
        $this->assertEquals(777, $data['ID']);
    }
    /**
     * Tests empty user delete attempt.
     * @group models
     * @group user
     */
    public function testEmptyUserDelete()
    {
        // Prepare
        $user = new User;
        // Run
        $deleted = $user->delete();
        // Assert
        $this->assertInternalType('bool', $deleted);
        $this->assertFalse($deleted);
    }
    /**
     * Tests empty user.
     * @group models
     * @group user
     */
    public function testNonExistent()
    {
        // Prepare and run
        $user = User::find(5000100);
        // Assert
        $this->assertNull($user);
    }
}