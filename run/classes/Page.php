<?php

class Page {

    protected $path;

    protected $data;
    protected $pages;
    protected $images;

    public function __construct( $path )
    {

        $this->path = $path;

        $this->data();
        $this->pages();
        $this->images();

    }

    public function path(): string {
        return $this->path;
    }

    public function data(): array
    {

        $this->data = [];

        $path = $this->path . DS . option('pageinfo');
        if( file_exists( $path ) ){

            $string = file_get_contents( $path );

            $this->data = json_decode( $string, TRUE );

        }

        return $this->data;
    }

    public function pages(): array
    {
        $this->pages = [];

        foreach( scandir( $this->path ) as $item ){

            $first = substr($item, 0, 1);
            if( $first === '.' || $first === '_' ){
                continue;
            }

            $path = $this->path . DS . $item;

            if( is_dir( $path ) ){
                $this->pages[] = new Page( $path );
            }

        }

        return $this->pages;
    }

    public function images(): array
    {
        $this->images = [];

        $images = preg_grep('~\.(jpeg|jpg|png)$~', scandir( $this->path ));

        foreach( $images as $item ){

            $first = substr($item, 0, 1);
            if( $first === '.' || $first === '_' ){
                continue;
            }

            $path = $this->path . DS . $item;
            $this->images[] = new Image( $path );

        }

        return $this->images;

    }

    public function toArray(): array
    {
        $pages = [];
        foreach( $this->pages as $page ){
            $pages[] = $page->toArray();
        }

        $images = [];
        foreach( $this->images as $image ){
            $images[] = $image->toArray();
        }

        return array_merge( $this->data, [
            'url' => str_replace( option('content').DS, '', $this->path ),
            'pages' => $pages,
            'images' => $images,
        ]);
    }

}
