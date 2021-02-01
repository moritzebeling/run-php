<?php

class Run {

    protected $request;
    protected $config;
    protected $routes;
    protected $template;
    protected $controller;
    protected $content;

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

    public function option( string $key = null )
    {
        if( array_key_exists( $key, $this->config() ) ){
            return $this->config()[ $key ];
        }
        return null;
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

    public function template( ?string $name = null ): string
    {
        if( $this->template ){
            return $this->template;
        }

        if( $name !== null ){
            if( is_file( $this->option('templates') . DS . $name ) ){
                return $this->option('templates') . DS . $name;
            }
        }

        foreach( $this->routes() as $route => $template ){

            if( preg_match( '/^' . str_replace( '*', '(.*)', $route) . '$/', $this->request() ) ){
                if( is_file( $this->option('templates') . DS . $template ) ){
                    return $this->option('templates') . DS . $template;
                }
            }

        }
        if( is_file( $this->option('templates') . DS . 'default.php' ) ){
            return $this->option('templates') . DS . 'default.php';
        }
        return 'run/config/template.php';
    }

    public function controller( ?string $name = null ): string
    {
        if( $this->controller ){
            return $this->controller;
        }

        $controller = $name !== null ? $name : $this->template();

        if( is_file( $this->option('controllers') . DS . $controller ) ){
            return $this->option('controllers') . DS . $controller;
        }
        if( is_file( $this->option('controllers') . DS . 'default.php' ) ){
            return $this->option('controllers') . DS . 'default.php';
        }
        return 'run/config/controller.php';
    }

    public function content(): Content
    {
        if( $this->content ){
            return $this->content;
        }

        $this->content = new Content();
        return $this->content;
    }

    public function debug(): array
    {

        return [
            'config' => $this->config(),
            'routes' => $this->routes(),
            'request' => $this->request(),
            'controller' => $this->controller(),
            'template' => $this->template(),
            'content' => $this->content(),
        ];

    }

    public function render()
    {

        $___data___ = call_user_func(
            File::load( $this->controller(), [] ),
            $this
        );

        extract( $___data___ );

        include $this->template();

    }

}
