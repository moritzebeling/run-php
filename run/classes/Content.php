<?php

class Content {

    protected $path;

    protected $content;

    public function __construct()
    {

        $this->content = new Page( option('content') );

    }

    public function content(){


        return $this->content;

    }

    public function toArray(): array
    {
        return $this->content->toArray()['pages'];
    }

}
