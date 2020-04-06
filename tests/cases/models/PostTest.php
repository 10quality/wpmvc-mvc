<?php
/**
 * Tests MVC post model.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.7
 */
class PostTest extends MVCTestCase
{
    /**
     * Tests model construction.
     * @group models
     */
    public function testConstruct()
    {
        // Prepare
        $post = new Post;
        // Run
        $array = $post->to_array();
        $this->assertInternalType('array', $array);
        $this->assertArrayHasKey('parent', $array);
        $this->assertArrayHasKey('posts', $array);
        $this->assertArrayHasKey('post_ids', $array);
        $this->assertArrayHasKey('post_parent', $array);
    }
    /**
     * Tests model find.
     * @group models
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
     * @group models
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
     * @group models
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
     * @group models
     */
    public function testCastingArray()
    {
        $post = Post::find(1);
        $post->setHidden([
            'post_name',
            'post_title',
            'post_content',
            'parent',
            'post_parent',
            'posts',
            'post_ids',
        ]);
        $this->assertEquals(['ID' => 1], $post->to_array());
    }
    /**
     * Tests model casting to string / json.
     * @group models
     */
    public function testCastingString()
    {
        $post = Post::find(1);
        $post->setHidden([
            'post_title',
            'post_content',
            'parent',
            'post_parent',
            'posts',
            'post_ids',
        ]);
        $this->assertEquals('{"ID":1,"post_name":"hello-world"}', (string)$post);
    }
    /**
     * Tests model casting to string / json.
     * @group models
     * @group relationships
     */
    public function testBelongsToRelationship()
    {
        // Prepare
        $post = Post::find(1);
        // Run
        $parent = $post->parent;
        // Assert
        $this->assertInstanceOf(Post::class, $parent);
        $this->assertEquals(15, $parent->ID);
        $this->assertEquals('Hello World 1', $post->post_title);
        $this->assertEquals('Parent', $parent->post_title);
    }
    /**
     * Tests model casting to string / json.
     * @group models
     * @group relationships
     */
    public function testHasManyRelationship()
    {
        // Prepare
        $post = Post::find(1);
        // Run
        $posts = $post->posts;
        // Assert
        $this->assertInternalType('array', $posts);
        $this->assertInstanceOf(Post::class, $posts[0]);
        $this->assertInstanceOf(Post::class, $posts[1]);
        $this->assertInstanceOf(Post::class, $posts[2]);
    }
}