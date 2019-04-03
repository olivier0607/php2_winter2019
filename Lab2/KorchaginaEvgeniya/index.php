<?php

ob_start();

require 'DataStore.php';
require 'TemplateManager.php';
require 'IndexController.php';

$app = new IndexController();

$app->indexAction();

ob_end_flush();
flush();
exit;



