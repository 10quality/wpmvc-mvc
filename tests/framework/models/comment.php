<?php

use WPMVC\MVC\Models\CommentModel;
use WPMVC\MVC\Traits\FindTrait;

class Comment extends CommentModel
{
    use FindTrait;
    protected function custom_slug()
    {
        return $this->comment_ID.'-slug';
    }
    protected function content_slug()
    {
        return $this->comment_content.'-slug';
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