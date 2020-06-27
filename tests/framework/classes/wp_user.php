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
        $this->data = new stdClass;
        $this->data->ID = $id;
        $this->data->first_name = 'John';
        $this->data->last_name = 'Doe';
        $this->data->user_login = 'admin';
        $this->data->user_email = 'admin@test.test';
    }
}