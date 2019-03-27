<?php

// Start output buffering.
ob_start();

// need autoloading
require __DIR__ . '/vendor/autoload.php';

$app = new App\IndexController();

$app->indexAction();

ob_end_flush();