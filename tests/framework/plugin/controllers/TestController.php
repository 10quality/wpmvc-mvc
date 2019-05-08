<?php

namespace WPMVC\Testing\Controllers;

use WPMVC\MVC\Controller;

class TestController extends Controller
{
    public function get()
    {
        return true;
    }
    public function param($param)
    {
        return $param;
    }
    public function view($view)
    {
        return $this->view->get($view);
    }
    public function user_id()
    {
        return $this->user->ID;
    }
}