<?php
class WP_Post extends stdClass
{
    public function __construct($id)
    {
        $this->ID = $id;
        $this->post_name = 'hello-world';
        $this->post_title = trim('Hello World ' . $id);
        $this->post_content = 'Hello World';
    }
}