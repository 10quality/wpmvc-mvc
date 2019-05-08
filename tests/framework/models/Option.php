<?php

use WPMVC\MVC\Models\OptionModel;
use WPMVC\MVC\Traits\FindTrait;

class Option extends OptionModel
{
    use FindTrait;
    protected $id = 'test';
    protected function concat_ab()
    {
        return $this->a.$this->b;
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