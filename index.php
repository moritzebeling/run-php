<?php

require_once 'run/start.php';

header('Content-Type: application/json');

$content = new Content();

echo json_encode( $content->toArray() );
