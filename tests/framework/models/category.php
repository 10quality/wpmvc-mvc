<?php

use WPMVC\MVC\Models\CategoryModel;
use WPMVC\MVC\Traits\FindTrait;

class Category extends CategoryModel
{
    use FindTrait;
    protected function concat_name()
    {
        return $this->name.$this->slug;
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