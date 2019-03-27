<?php
namespace Config\Route;

$arrayURI = explode('/', $_SERVER['REQUEST_URI']);

$method = array_pop($arrayURI) . 'Action';

$class = ucfirst(array_pop($arrayURI));

$fileName = $class . '.php';

require $fileName;

$handler = new $class();

$result = $handler->{$method}();

echo $result;