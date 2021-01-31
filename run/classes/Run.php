<?php

class Run {

    protected $request;
    protected $routes;
    protected $controller;
    protected $template;

    public function __construct()
    {

    }

    public function request(): string
    {
        if( $this->request ){
            return $this->request;
        }

        $this->request = trim( $_SERVER['REQUEST_URI'], '/' );
        return $this->request;
    }

    public function config(): array
    {
        if( $this->config ){
            return $this->config;
        }

        $defaults = File::load('run/config/config.php', []);
        $settings = File::load('theme/config/config.php', []);

        $this->config = array_replace_recursive( $defaults, $settings );
        return $this->config;
    }

    public function routes(): array
    {
        if( $this->routes ){
            return $this->routes;
        }

        $defaults = File::load('run/config/routes.php', []);
        $settings = File::load('theme/config/routes.php', []);

        $this->routes = array_replace_recursive( $defaults, $settings );
        return $this->routes;
    }

    public function template( ?string $fallback = 'default.php' ): string
    {
        if( $this->template ){
            return $this->template;
        }

        foreach( $this->routes() as $route => $template ){

            if( preg_match( '/^' . str_replace( '*', '(.*)', $route) . '$/', $this->request() ) ){

                return $template;
            }

        }
        return $fallback;
    }

    public function render(){

        return $this->request();

    }

}
