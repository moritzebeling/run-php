<?php

class Assets {

    public static function css( string $name ): string
    {
        return '<link rel="stylesheet" href="'. $name .'">';
    }

    public static function js( string $name ): string
    {
        return '<script src="'. $name .'"></script>';
    }

}
