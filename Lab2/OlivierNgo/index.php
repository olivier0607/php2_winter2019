<?php
require 'DataStorage.php';
require 'TemplateManager.php';
require 'IndexController.php';
require 'CheckLogin.php';
require 'Session.php';

$dataStore = new DataStorage();
$session = new Session();
$checkLog = new CheckLogin($dataStore);

$app = new IndexController($dataStore,$session,$checkLog);

$app->indexActions();




