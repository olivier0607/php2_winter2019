<?php

// Start output buffering.
ob_start();

include 'vendor/autoload.php';

// need routing

$app = new \Application\Router();

ob_end_flush();