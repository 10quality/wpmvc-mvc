<?php
/**
 * Tests MVC post model.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
class PostTest extends MVCTestCase
{
    /**
     * Tests model construction.
     */
    public function testConstruct()
    {
        $post = new Post;

        $this->assertEquals($post->to_array(), []);
    }

    /**
     * Tests model find.
     */
    public function testFind()
    {
        $post = Post::find(1);

        $this->assertEquals($post->ID, 1);

        $this->assertEquals($post->post_name, 'hello-world');

        $this->assertEquals($post->type, 'test');
    }

    /**
     * Tests model aliases.
     */
    public function testAliases()
    {
        $post = Post::find(1);
        $post->setAliases([
            'slug'  => 'post_name',
            'name'  => 'func_concat_name',
        ]);

        $this->assertEquals($post->slug, 'hello-world');

        $this->assertEquals($post->name, 'hello-worldhello-world');

        $post->slug = 'test';

        $this->assertEquals($post->slug, 'test');

        $this->assertEquals($post->post_name, 'test');
    }

    /**
     * Tests model meta.
     */
    public function testMeta()
    {
        $post = Post::find(1);
        $post->setAliases([
            'views'  => 'meta_views',
        ]);

        $this->assertNull($post->views);

        $post->views = 99;

        $this->assertEquals($post->views, 99);

        $this->assertTrue($post->has_meta('views'));

        $this->assertFalse($post->has_meta('viewed'));

        $this->assertTrue($post->save());
    }

    /**
     * Tests model casting to array.
     */
    public function testCastingArray()
    {
        $post = Post::find(1);
        $post->setHidden([
            'post_name',
            'post_title',
            'post_content',
        ]);

        $this->assertEquals($post->to_array(), ['ID' => 1]);
    }

    /**
     * Tests model casting to string / json.
     */
    public function testCastingString()
    {
        $post = Post::find(1);
        $post->setHidden([
            'post_title',
            'post_content',
        ]);

        $this->assertEquals((string)$post, '{"ID":1,"post_name":"hello-world"}');
    }
}