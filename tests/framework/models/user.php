<?php

use WPMVC\MVC\Models\UserModel;
use WPMVC\MVC\Traits\FindTrait;

class User extends UserModel
{
    use FindTrait;
    protected function fullname()
    {
        return $this->first_name.' '.$this->last_name;
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