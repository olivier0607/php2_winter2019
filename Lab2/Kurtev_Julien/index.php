<?php

// Start output buffering.
ob_start();

// need autoloading
require 'DataStorage.php';
require 'TemplateManager.php';
require 'IndexController.php';

// need routing

$app = new IndexController();

$app->indexAction();

ob_end_flush();