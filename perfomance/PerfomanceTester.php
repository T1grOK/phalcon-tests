<?php

define('APP_PATH', __DIR__.'/');

require_once APP_PATH.'Bootstrap.php';

try
{
    $bootstrap = new Bootstrap($argv);
    $bootstrap->handle();
}
catch (Phalcon\Exception $e)
{
    echo $e->getMessage();
}

