<?php
class WP_User extends stdClass
{
    public function __construct($id)
    {
        $this->ID = $id;
        $this->caps = ['manage_options'];
        $this->cap_key = 'manage_options';
        $this->roles = ['administrator'];
        $this->allcaps = $this->caps;
        $this->first_name = 'John';
        $this->last_name = 'Doe';
        $this->user_login = 'admin';
    }
}