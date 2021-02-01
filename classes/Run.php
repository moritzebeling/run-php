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

    public function template(): Template
    {
        if( $this->template === null ){
            $this->template = Template::findByRoute( $this->request(), $this->routes() );
        }
        return $this->template;
    }

    public function controller(): Controller
    {
        if( $this->controller === null ){
            $this->controller = new Controller( $this->template()->name() );
        }
        return $this->controller;
    }

    public function content(): Content
    {
        if( $this->content === null ){
            $this->content = new Content();
        }
        return $this->content;
    }

    public function debug(): array
    {

        return [
            'config' => $this->config(),
            'routes' => $this->routes(),
            'request' => $this->request(),
            'controller' => $this->controller()->debug(),
            'template' => $this->template()->debug(),
            'content' => $this->content(),
        ];

    }

    public function render()
    {

        $___data___ = call_user_func( include $this->controller()->file(), $this->content() );

        extract( $___data___ );

        include $this->template()->file();

    }

}
