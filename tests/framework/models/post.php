<?php

use WPMVC\MVC\Models\PostModel;
use WPMVC\MVC\Traits\FindTrait;

class Post extends PostModel
{
    use FindTrait;
    protected $type = 'test';
    protected $aliases = [
        'post_ids' => 'func_get_post_ids',
    ];
    protected $attributes = [
        'post_parent' => 2,
    ];
    /*
    protected function image()
    {
        return $this->has_featured();
    }
    */
    protected function parent()
    {
        return $this->belongs_to(Post::class, 'post_parent');
    }
    protected function posts()
    {
        return $this->has_many(Post::class, 'post_ids');
    }
    protected function concat_name()
    {
        return $this->post_name.$this->post_name;
    }
    public function setAliases($aliases)
    {
        $this->aliases = $aliases;
    }
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }
    public static function echo()
    {
        return 'echo';
    }
    protected function method()
    {
        return true;
    }
    protected function get_post_ids()
    {
        $ids = [];
        $ids[] = rand( $this->ID, 500 );
        $ids[] = rand( count($ids)-1, 750 );
        $ids[] = rand( count($ids)-1, 1000 );
        return $ids;
    }
}