<?php

class TestObject
{
    protected $attributes = [];
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
    public function toArray()
    {
        return $attributes;
    }
}