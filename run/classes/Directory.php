<?php

class Direct {

    protected $path;
    protected $list;

    public function __construct( $path )
    {
        $this->path = $path;
    }

    public function list(): array
    {
        if( $this->list !== null ){
            return $this->list;
        }

        $this->list = [];

        foreach( scandir( $this->path ) as $item ){

            $first = substr($item, 0, 1);
            if( $first === '.' || $first === '_' ){
                continue;
            }

            $path = $this->path . DS . $item;
            $this->list[$item] = $path;

        }

        return $this->list;
    }

    public function files(): array
    {
        $files = [];
        foreach( $this->list() as $id => $path ){

            if( file_exists( $path ) ){
                $files[] = $path;
            }

        }
        return $files;
    }

}
