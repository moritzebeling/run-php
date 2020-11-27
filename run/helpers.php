<?php

function dump( $var ){
    echo '<pre>';
    print_r( $var );
    echo '</pre>';
}

function slug( string $string ){
    $string = trim( $string );
    $string = strtolower( $string );

    $string = preg_replace('/[^a-z0-9]+/', '-', $string);

    return substr( $string, 0, 64 );
}
