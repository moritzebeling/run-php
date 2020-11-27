<?php

class Content {

    protected $path = 'portfolio';
    protected $content;

    public function __construct()
    {

        $this->content();

    }

    public function content(){

        $this->content = new Page( $this->path );

    }

    public function toArray(): array
    {
        return $this->content->toArray();
    }

}
