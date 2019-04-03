<?php

require 'SessionService.php';

class IndexController
{
    protected $errorMes=0;
    protected  $userMes="";
    protected $varSession="";
    protected $postLoginForm = true;
    protected $sessionUser = "";

    public function indexAction()
    {
        $dataStore = new DataStore();
        $sessionService = new SessionService($dataStore);
        $viewManager = new TemplateManager();

        $sessionService->loginVerification();

        $this->postLoginForm = $sessionService->getPostLoginForm();
        $this->userMes = $sessionService->getUserMessage();

        if ($this->postLoginForm && isset($_POST['signup']))
            $templateName = 'signup';
        elseif ($this->postLoginForm) {
            $templateName = 'signin';
        } else {
            $templateName = 'index';
        }

        $viewManager->loadTemplate($templateName, $this->userMes);
        $viewManager->render();
    }

}