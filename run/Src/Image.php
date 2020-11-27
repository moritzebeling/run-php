<?php

class Image {

    public $url;
    public $path;
    public $title;
    public $filename;
    public $extension;

    public function __construct( $url )
    {

        $this->url = $url;

        $parts = explode('/', $url );

        $this->filename = array_pop( $parts );
        $this->path = implode( '/', $parts );

        $parts = explode('.', $this->filename );

        $this->extension = array_pop( $parts );
        $this->title = implode( '.', $parts );

    }

    public function toArray(): array {
        return [
            'url' => $this->url,
            'path' => $this->path,
            'filename' => $this->filename,
            'extension' => $this->extension,
            'title' => $this->title,
            'srcset' => [
                '600' => (new Thumb( $this, 600))->url(),
                '1200' => (new Thumb( $this, 1200))->url(),
                '2000' => (new Thumb( $this, 2000))->url(),
            ]
        ];
    }

}
