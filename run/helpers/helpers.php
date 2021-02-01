<?php

function dump( $var ){
    echo '<pre>';
    print_r( $var );
    echo '</pre>';
}

function toSlug( string $string ){
    $string = trim( $string );
    $string = strtolower( $string );

    $string = preg_replace('/[^a-z0-9]+/', '-', $string);

    return substr( $string, 0, 64 );
}

function run(){
    global $app;
    return $app;
}

function option( string $key ){
    return run()->option( $key );
}

function controller( ?string $name = 'default', $data = [] ){

    $file = ROOT . "/theme/controllers/$name.php";
    if( file_exists( $file ) ){
        require_once $file;
    }

}

function template( ?string $name = 'default' ){

    $cool = 'sehgf';

    $file = ROOT . "/theme/templates/$name.php";
    if( file_exists( $file ) ){
        require_once $file;
    }

}

function snippet( string $name ){

    $file = ROOT . "/theme/templates/snippets/$name.php";
    if( file_exists( $file ) ){
        require_once $file;
    }

}

function css( string $filename ){

    if( substr( $filename, 0, 4 ) !== "http" ){
        $filename = "/theme/css/$filename";
        if( !file_exists( ROOT . $filename ) ){
            return;
        }
    }
    return "<link rel='stylesheet' href='$filename'>";

}

function js( string $filename ){

    if( substr( $filename, 0, 4 ) !== "http" ){
        $filename = "/theme/js/$filename";
        if( !file_exists( ROOT . $filename ) ){
            return;
        }
    }
    return "<script defer src='$filename'></script>";

}
