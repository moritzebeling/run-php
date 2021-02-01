<?php

class Content {

    protected $path;
    protected $content;
    protected $pages;

    public function __construct()
    {
        $this->path = option('content');
    }

    public function content(): Page
    {
        if( $this->content === null ){
            $this->content = new Page( $this->path );
        }
        return $this->content;
    }

    public function pages(): array
    {
        if( $this->pages === null ){
            $this->pages = $this->content()->pages();
        }
        return $this->pages;
    }

    public function toArray(): array
    {
        return $this->content->toArray()['pages'];
    }

    public function debug(): array
    {
        return [
            'path' => $this->path,
            'content' => $this->content,
            'pages' => $this->pages
        ];
        return $this->content->toArray()['pages'];
    }

}
