<?php

class File {

    protected $url;
    protected $path;
    protected $name;
    protected $filename;
    protected $extension;

    public function __construct( $url )
    {

        $this->url = $url;

        $parts = explode( DS, $url );

        $this->filename = array_pop( $parts );
        $this->path = implode( DS, $parts );

        $parts = explode( '.', $this->filename );

        $this->extension = array_pop( $parts );
        $this->name = implode( '.', $parts );

    }

    public function url(): string {
        return $this->url;
    }

    public function path(): string {
        return $this->path;
    }

    public function name(): string {
        return $this->name;
    }

    public function filename(): string {
        return $this->filename;
    }

    public function extension(): string {
        return $this->extension;
    }

    public function title(): string {
        $parts = explode( '_', $this->name() );
        if( count( $parts ) > 1 ){
            array_shift( $parts );
        }
        foreach( $parts as $i => $part ){
            $parts[$i] = ucfirst( str_replace('-',' ',$part) );
        }
        return implode(' ', $parts);
    }

    public function toArray(): array {
        return [
            'url' => $this->url(),
            'path' => $this->path(),
            'filename' => $this->filename(),
            'title' => $this->title(),
            'extension' => $this->extension(),
        ];
    }

}
