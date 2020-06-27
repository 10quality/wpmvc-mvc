<?php
/**
 * Tests MVC comment model.
 *
 * @author Cami M
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.11
 */
class CommentTest extends MVCTestCase
{
    /**
     * Tests model construction.
     * @group models
     * @group comments
     */
    public function testConstruct()
    {
        $comment = new Comment;
        $this->assertEquals([], $comment->to_array());
    }
    /**
     * Tests model find.
     * @group models
     * @group comments
     */
    public function testFind()
    {
        $comment = Comment::find(1);
        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals(1, $comment->comment_ID);
        $this->assertEquals('comment1', $comment->comment_content);
    }
    /**
     * Tests model aliases.
     * @group models
     * @group comments
     */
    public function testAliases()
    {
        $comment = Comment::find(123);
        $comment->setAliases([
            'content' => 'comment_content',
            'slug' => 'func_custom_slug',
            'content_slug' => 'func_content_slug',
        ]);
        $this->assertEquals('comment123', $comment->content);
        $this->assertEquals('123-slug', $comment->slug);
        $this->assertEquals('comment123-slug', $comment->content_slug);
        $comment->content = 'test';
        $this->assertEquals('test', $comment->content);
        $this->assertEquals('test-slug', $comment->content_slug);
    }
    /**
     * Tests model meta.
     * @group models
     * @group comments
     */
    public function testMeta()
    {
        $comment = Comment::find(1);
        $comment->setAliases([
            'reviews'  => 'meta_reviews',
            'views'  => 'meta_views',
        ]);
        $this->assertNotNull($comment->views);
        $this->assertNull($comment->reviews);
        $comment->reviews = 1;
        $this->assertEquals(1, $comment->reviews);
        $this->assertEquals(99, $comment->views);
        $this->assertEquals(1, $comment->get_meta('reviews'));
        $this->assertEquals(99, $comment->get_meta('views'));
        $this->assertTrue($comment->has_meta('reviews'));
    }
    /**
     * Tests model casting to array.
     * @group models
     * @group comments
     */
    public function testCastingArray()
    {
        $comment = Comment::find(1);
        $comment->setHidden([
            'comment_post_ID',
            'user_id',
        ]);
        $this->assertEquals(['comment_ID' => 1, 'comment_type' => 'comment', 'comment_content' => 'comment1'], $comment->to_array());
    }
    /**
     * Tests model casting to string / json.
     * @group models
     * @group comments
     */
    public function testCastingString()
    {
        $comment = Comment::find(2);
        $comment->setAliases([
            'content' => 'comment_content',
            'slug' => 'func_custom_slug',
        ]);
        $comment->setHidden([
            'comment_post_ID',
            'user_id',
        ]);
        $this->assertEquals('{"comment_ID":2,"comment_type":"comment","content":"comment2","slug":"2-slug"}', (string)$comment);
    }
    /**
     * Tests model method.
     * @group models
     * @group comments
     */
    public function testFromArray()
    {
        $comment = new Comment;
        $comment->from_array(['comment_ID' => 55, 'comment_content' => 'test']);
        $this->assertEquals(55, $comment->comment_ID);
        $this->assertEquals('test', $comment->comment_content);
    }
    /**
     * Tests empty post.
     * @group models
     * @group comments
     */
    public function testNonExistent()
    {
        // Prepare and run
        $model = Comment::find(5000100);
        // Assert
        $this->assertNull($model);
    }
    /**
     * Tests loading model using a WP_User object.
     * @group models
     * @group comments
     */
    public function testLoadFromWpComment()
    {
        // Prepare
        $wp_comment = new WP_Comment(505);
        $comment = new Comment;
        // Run
        $comment->load_wp_comment( $wp_comment );
        // Assert
        $this->assertEquals(505, $comment->comment_ID);
        $this->assertEquals('comment505', $comment->comment_content);
    }
    /**
     * Tests return of WP_Comment.
     * @group models
     * @group comments
     */
    public function testGetWpContent()
    {
        // Prepare
        $comment = Comment::find(56);
        // Run
        $wp_comment = $comment->wp_comment();
        // Assert
        $this->assertInstanceOf(WP_Comment::class, $wp_comment);
        $this->assertEquals(56, $wp_comment->comment_ID);
        $this->assertEquals('comment56', $wp_comment->comment_content);
        $this->assertNotEmpty($wp_comment->user_id);
    }
    /**
     * Tests insert.
     * @group models
     * @group comments
     */
    public function testInsert()
    {
        // Prepare
        $comment = new Comment;
        // Run
        $comment->user_id = 66;
        $comment->comment_content = 'inserted';
        $inserted = $comment->save();
        // Assert
        global $data;
        $this->assertInternalType('bool', $inserted);
        $this->assertTrue($inserted);
        $this->assertEquals(101, $comment->comment_ID);
        $this->assertEquals('wp_insert_comment', $data['trigger']);
        $this->assertEquals(66, $data['user_id']);
        $this->assertEquals('inserted', $data['comment_content']);
    }
    /**
     * Tests update.
     * @group models
     * @group comments
     */
    public function testUpdate()
    {
        // Prepare
        $comment = new Comment(777);
        $comment->setAliases([
            'content' => 'comment_content',
            'views' => 'meta_views',
            'calc'  => 'func_calc',
        ]);
        // Run
        $comment->content = 'test';
        $updated = $comment->save();
        // Assert
        global $data;
        $this->assertInternalType('bool', $updated);
        $this->assertTrue($updated);
        $this->assertEquals(777, $comment->comment_ID);
        $this->assertEquals('wp_update_comment', $data['trigger']);
        $this->assertArrayHasKey('comment_content', $data);
        $this->assertArrayHasKey('comment_ID', $data);
        $this->assertArrayNotHasKey('user_id', $data);
        $this->assertEquals(777, $data['comment_ID']);
        $this->assertEquals('test', $data['comment_content']);
    }
    /**
     * Tests delete.
     * @group models
     * @group comments
     */
    public function testDelete()
    {
        // Prepare
        $comment = new Comment(777);
        // Run
        $deleted = $comment->delete();
        // Assert
        global $data;
        $this->assertInternalType('bool', $deleted);
        $this->assertTrue($deleted);
        $this->assertEquals('wp_delete_comment', $data['trigger']);
        $this->assertEquals(777, $data['comment_ID']);
    }
    /**
     * Tests empty delete attempt.
     * @group models
     * @group comments
     */
    public function testEmptyDelete()
    {
        // Prepare
        $comment = new Comment;
        // Run
        $deleted = $comment->delete();
        // Assert
        $this->assertInternalType('bool', $deleted);
        $this->assertFalse($deleted);
    }
}