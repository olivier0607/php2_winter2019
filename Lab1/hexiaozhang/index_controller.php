<?php
require 'hexiao.php';
require 'dataStore.php';

class IndexController
{
    protected $data=[];
    protected $viewManager;

    public function indexAction(){

        $dataStore = new dataStore();
        $this->data = $dataStore ->getusers();
        $this->viewManager = new TemplateManager();
        $this->viewManager-> setData($this->data);
        $this->viewManager->loadTemplate();
        $this->viewManager->render();

    }
}
