<?php

class Template {

    protected $name;
    protected $path;
    protected $file;

    public function __construct( string $path, string $name )
    {
        $this->name = $name;
        $this->path = $path;
        $this->file = $path . DS . $name;
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

    public static function findByRoute( ?string $request, ?array $routes ): Template
    {
        $path = option('templates');
        $name = null;

        foreach( $routes as $route => $t ){
            if( preg_match( '/^' . str_replace( '*', '(.*)', $route) . '$/', $request ) ){
                if( self::exists( $path . DS . $t ) ){
                    $name = $t;
                }
            }
        }

        if( $name === null && self::exists( $path . DS . 'default.php' ) ){
            $name = 'default.php';
        }

        if( $name === null ){
            $path = 'run/config';
            $name = 'template.php';
        }

        return new Template( $path, $name );
    }

    public static function exists( $file ): bool
    {
        return is_file( $file );
    }

    public function include()
    {
        if( $this->exists( $this->file ) ){
            return '';
        }
        include $this->file;
    }

}
