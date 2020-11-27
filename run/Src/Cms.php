<?php

class Cms {

    protected $config;

    public function __construct()
    {
        $this->config = 'Test';
    }

    public function render(): string
    {
        return $this->config;
    }

}
