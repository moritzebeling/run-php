<?php

class Content {

    protected $path = 'content';
    protected $content;

    public function __construct()
    {

        $this->content();

    }

    public function content(){

        $this->content = new Page( $this->path );
        return $this->content;

    }

    public function toArray(): array
    {
        return $this->content->toArray()['pages'][0];
    }

}
