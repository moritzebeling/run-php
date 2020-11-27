<?php

class File {

    protected $path;

    public function __construct( $path )
    {
        $this->path = $path;
    }

    public function toArray(): array {
        return [
            'url' => $this->path
        ];
    }

}
