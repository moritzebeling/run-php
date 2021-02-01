<?php

return function ( $run ) {

    return [
        'data' => $run->content()->toArray()
    ];

};
