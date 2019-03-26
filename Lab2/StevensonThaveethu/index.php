<?php
/**
 * 
 * Lab 2
 * 
 * Author: Stevenson Thaveethu
 * Date: March 6, 2019
 *
 */

require 'Model/AppModel.php';
require 'View/AppView.php';
require 'Controller/AppController.php';
require 'Library/AppSessionManager.php';

$appModel = new AppModel();
$appSessionManager = new AppSessionManager($appModel);

$app = new AppController($appModel, $appSessionManager);

$app->webActions();
