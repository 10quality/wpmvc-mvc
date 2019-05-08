<?php

use WPMVC\MVC\Models\TermModel;
use WPMVC\MVC\Traits\FindTermTrait;

class Term extends TermModel
{
    use FindTermTrait;

    protected $model_taxonomy = 'test-tax';

    protected function the_slug()
    {
        return $this->name.'|'.$this->slug;
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