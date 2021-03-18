<?php
/**
 * Tests MVC term model.
 *
 * @author Cami M
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.1.11
 */
class TermTest extends MVCTestCase
{
    /**
     * Tests model construction.
     * @group models
     */
    public function testConstruct()
    {
        $term = new Term;

        $this->assertEquals($term->to_array(), []);
    }
    /**
     * Tests model find.
     * @group models
     */
    public function testFind()
    {
        $term = Term::find(1, 'test');
        $this->assertEquals(1, $term->term_id);
        $this->assertEquals('term-1', $term->slug);
        $this->assertEquals('test', $term->taxonomy);
    }
    /**
     * Tests model aliases.
     * @group models
     */
    public function testAliases()
    {
        $term = Term::find(1);
        $term->setAliases([
            'qs'        => 'slug',
            'the_slug'  => 'func_the_slug',
        ]);
        $this->assertEquals('term-1', $term->qs);
        $this->assertEquals('Term ID:1|term-1', $term->the_slug);
        $term->qs = 'test';
        $this->assertEquals('test', $term->slug);
    }
    /**
     * Tests model meta.
     * @group models
     */
    public function testMeta()
    {
        $term = Term::find(1);
        $term->setAliases([
            'views'  => 'meta_views',
        ]);
        $this->assertNull($term->views);
        $term->views = 99;
        $this->assertEquals($term->views, 99);
        $this->assertTrue($term->has_meta('views'));
        $this->assertTrue($term->save());
    }
    /**
     * Tests model casting to array.
     * @group models
     */
    public function testCastingArray()
    {
        $term = Term::find(1);
        $term->setHidden([
            'name',
            'slug',
            'taxonomy',
        ]);
        $this->assertEquals(['term_id' => 1], $term->to_array());
    }
    /**
     * Tests model casting to string / json.
     * @group models
     */
    public function testCastingString()
    {
        $term = Term::find(1);
        $term->setHidden([
            'name',
            'slug',
        ]);
        $this->assertEquals('{"term_id":1,"taxonomy":"test-tax"}', (string)$term);
    }
    /**
     * Tests model find.
     * @group models
     */
    public function testFindBySlug()
    {
        $term = Term::find_by_slug('test', 'custom');
        $this->assertEquals(404, $term->term_id);
        $this->assertEquals('test', $term->slug);
        $this->assertEquals('custom', $term->taxonomy);
    }
    /**
     * Tests model method.
     * @group models
     */
    public function testAll()
    {
        $terms = Term::all('test');
        $this->assertEquals(2, count($terms));
        $this->assertEquals(1, $terms[0]->term_id);
        $this->assertEquals('test', $terms[0]->taxonomy);
        $this->assertEquals(2, $terms[1]->term_id);
        $this->assertEquals('test', $terms[1]->taxonomy);
    }
    /**
     * Tests model method.
     * @group models
     */
    public function testFromTerm()
    {
        $term = new Term;
        $term->from_term(new WP_Term(1, 'tax'));
        $this->assertEquals(1, $term->term_id);
        $this->assertEquals('tax', $term->taxonomy);
    }
    /**
     * Tests model method.
     * @group models
     */
    public function testFromArray()
    {
        $term = new Term;
        $term->from_array(['term_id' => 55, 'taxonomy' => 'test']);
        $this->assertEquals(55, $term->term_id);
        $this->assertEquals('test', $term->taxonomy);
    }
    /**
     * Tests empty post.
     * @group models
     * @group terms
     */
    public function testNonExistent()
    {
        // Prepare and run
        $model = Term::find(5000100);
        // Assert
        $this->assertNull($model);
    }
    /**
     * Tests empty post.
     * @group models
     * @group terms
     */
    public function testNonExistentBy()
    {
        // Prepare and run
        $model = Term::find_by_slug('error');
        // Assert
        $this->assertNull($model);
    }
    /**
     * Tests term insert.
     * @group models
     * @group terms
     */
    public function testInsert()
    {
        // Prepare
        $model = new Term;
        $model->slug = 'Test';
        // Run
        $saved = $model->save();
        // Assert
        $this->assertTrue($saved);
        $this->assertNotNull($model->term_id);
        $this->assertNotNull($model->term_taxonomy_id);
        $this->assertInternalType('int', $model->term_id );
        $this->assertInternalType('int', $model->term_taxonomy_id );
        $this->assertEquals($model->term_id, $model->term_taxonomy_id);
    }
}