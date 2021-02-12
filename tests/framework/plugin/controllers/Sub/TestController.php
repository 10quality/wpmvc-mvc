<?php

namespace WPMVC\Testing\Controllers\Sub;

use WPMVC\MVC\Controller;

class TestController extends Controller
{
    public function sub()
    {
        return true;
    }
    public function string()
    {
        return 'This is a test sub';
    }
}