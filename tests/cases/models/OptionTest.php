<?php
/**
 * Tests MVC option model.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.7
 */
class OptionTest extends MVCTestCase
{
    /**
     * Tests model construction.
     * @group models
     */
    public function testConstruct()
    {
        $option = new Option;
        $this->assertNotEmpty($option->to_array());
    }
    /**
     * Tests model find.
     * @group models
     */
    public function testFind()
    {
        $option = Option::find();
        $option->setAliases([
            'ID'        => 'field_ID',
            'a'         => 'field_a',
            'b'         => 'field_b',
            'isSetup'   => 'field_isSetup',
        ]);
        $this->assertEquals($option->ID, 'test');
        $this->assertFalse($option->isSetup);
    }
    /**
     * Tests model aliases.
     * @group models
     */
    public function testAliases()
    {
        $option = Option::find();
        $option->setAliases([
            'avalue'    => 'field_a',
            'a'         => 'field_a',
            'b'         => 'field_b',
            'ab'        => 'func_concat_ab',
        ]);
        $this->assertEquals($option->avalue, 'A value');
        $this->assertEquals($option->ab, 'A valueB value');
        $option->avalue = 'test';
        $this->assertEquals($option->a, 'test');
        $this->assertEquals($option->field_a, 'test');
    }
    /**
     * Tests model casting to array.
     * @group models
     */
    public function testCastingArray()
    {
        $option =  Option::find();
        $option->setAliases([
            'ID'        => 'field_ID',
        ]);
        $option->setHidden([
            'field_a',
            'field_b',
            'field_isSetup',
        ]);
        $this->assertEquals($option->to_array(), ['ID' => 'test']);
    }
    /**
     * Tests model casting to string / json.
     * @group models
     */
    public function testCastingString()
    {
        $option =  Option::find();
        $option->setAliases([
            'ID'        => 'field_ID',
            'isSetup'   => 'field_isSetup',
        ]);
        $option->setHidden([
            'field_a',
            'field_b',
        ]);
        $this->assertEquals((string)$option, '{"ID":"test","isSetup":false}');
    }
}