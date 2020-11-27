<?php

class Theme {

    protected $path = 'theme';

    public function __construct()
    {

    }

    public function css(): string
    {
        $r = '';
        foreach( (new Direct( $this->path . DS . 'css' ))->files() as $file ){
            $r .= Assets::css( $file );
        }
        return $r;
    }

    public function js(): string
    {
        $r = '';
        foreach( (new Direct( $this->path . DS . 'js' ))->files() as $file ){
            $r .= Assets::js( $file );
        }
        return $r;
    }

}
