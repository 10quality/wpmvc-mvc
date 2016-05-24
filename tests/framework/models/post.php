<?php

use WPMVC\MVC\Models\PostModel;
use WPMVC\MVC\Traits\FindTrait;

class Post extends PostModel
{
    use FindTrait;

    protected $type = 'test';

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
}