<?php
/**
 * Tests MVC global functions.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.11.3
 */
class FunctionsTest extends MVCTestCase
{
    /**
     * Tests global function autoload.
     * @since 2.1.11.3
     * @group functions
     */
    public function testAutoload()
    {
        $this->assertTrue(function_exists('wpmvc_array_value'));
    }
    /**
     * Tests function wpmvc_array_value.
     * @since 2.1.11.3
     * @group functions
     * @requires function wpmvc_array_value
     * @dataProvider provider_wpmvc_array_value
     *
     * @param mixed $value
     * @param mixed $expected
     */
    public function test_wpmvc_array_value( $value, $expected, $expectedType )
    {
        // Prepare and run
        $result = wpmvc_array_value( $value );
        // Assert
        $this->assertInternalType($expectedType, $result);
        $this->assertEquals($expected, $result);
    }
    /**
     * Returns dataset for test.
     * @since 2.1.11.3
     * @see self::test_wpmvc_array_value()
     * @return array
     */
    public function provider_wpmvc_array_value()
    {
        // Object
        $object = new stdClass;
        $object->a = 123;
        $object->b = 456;
        $object->c = new stdClass;
        $object->c->x = 'abc';
        $object->c->z = 'xyz';
        // Model
        $post = Post::find(1);
        $post->setAliases([
            'slug'  => 'post_name',
            'name'  => 'func_concat_name',
        ]);
        $post->setHidden([
            'slug',
        ]);
        // Aditional casting
        $obj = new TestObject;
        $obj->a = 123;
        $obj->b = 456;
        $obj->setAttributes([
            'id'  => 707,
            'name'  => 'test',
        ]);
        return [
            [123, 123, 'int'],
            ['abc', 'abc', 'string'],
            [true, true, 'boolean'],
            [12.3, 12.3, 'float'],
            [new stdClass, [], 'array'],
            [$object, ['a'=>123, 'b'=>456, 'c'=>['x'=>'abc','z'=>'xyz']], 'array'],
            [$post, ['name'=>'hello-worldhello-world'], 'array'],
            [$obj, ['id'=>707,'name'=>'test'], 'array'],
        ];
    }
}