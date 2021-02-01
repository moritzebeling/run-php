<?php

class Controller {

    protected $name;
    protected $path;
    protected $file;

    public function __construct( ?string $name = 'default.php' )
    {
        $this->find( $name );
    }

    public function __call( string $property, $arguments)
    {
        return $this->{$property};
    }

    public function debug(): array
    {
        return [
            'name' => $this->name(),
            'path' => $this->path(),
            'file' => $this->file(),
        ];
    }

    protected function find( string $name )
    {

        $this->path = option('controllers');

        if( self::exists( $this->path . DS . $name ) ){
            $this->name = $name;
        } else if( self::exists( $this->path . DS . 'default.php' ) ){
            $this->name = 'default.php';
        } else {
            $this->path = 'run/config';
            $this->name = 'controller.php';
        }

        $this->file = $this->path . DS . $this->name;
        return $this->file;
    }

    public static function exists( $file ): bool
    {
        return is_file( $file );
    }

}
