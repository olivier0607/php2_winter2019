<?php

require 'IndexController.php';


$dataStore = new DataStore();

$app = new IndexController($dataStore);

$app->indexAction();