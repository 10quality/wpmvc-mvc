<?php
class WP_Term extends stdClass
{
    public function __construct($id, $tax)
    {
        $this->term_id = $id;
        $this->slug = 'term-'.$id;
        $this->name = 'Term ID:'.$id;
        $this->taxonomy = $tax;
        $this->description = 'Desc '.$id;
    }
}