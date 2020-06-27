<?php
class WP_Comment extends stdClass
{
    public function __construct($id)
    {
        $this->comment_ID = $id;
        $this->comment_post_ID = $id + 1;
        $this->user_id = rand(1, 100);
        $this->comment_type = 'comment';
        $this->comment_content = 'comment' . $id;
    }
    public function to_array()
    {
        return get_object_vars($this);
    }
}