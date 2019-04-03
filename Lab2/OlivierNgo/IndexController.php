<?php

//require_once 'DataStorage.php';
//require_once 'Session.php';
class IndexController
{
    protected $dataStorage;
    protected $data = [];
    protected $viewManager;
    protected $session;
    protected $checkLogin;



    public function __construct(DataStorage $dataStorage, Session $session, CheckLogin $checkLogin)
    {
        $this->dataStorage = $dataStorage;
        $this->session = $session;
        $this->checkLogin = $checkLogin;

    }


    public function getData()
    {
        return $this->data;
    }


    public function setData($userMessage,$errorMessage,$postLoginForm)
    {
        $this->data['userMessage'] = $userMessage;
        $this->data['errorMessage'] = $errorMessage;
        $this->data['postLoginForm'] = $postLoginForm;
        return $this->data;
    }


    public function indexActions()
    {

        // Start output buffering.
        ob_start();

        // Set flags.
        $loginCheck = FALSE;

        $validSession = FALSE;

        $postLoginForm = TRUE;

        // Initialize application business and frontend messages.
        $errorMessage = 0;

        $userMessage = '';

        // Check if user is already logged in.
        if (isset($_COOKIE['loggedin'])) {

            if ($validSession === FALSE) {

                $validSession = $this->session->session_secure_init();

            }

            //  Check for cookie tampering.
            if ($validSession === TRUE && isset($_SESSION['LOGGEDIN'])) {

                $postLoginForm = FALSE;

            } else {

                $validSession = $this->session->session_obliterate();

                $errorMessage = 3;

                $postLoginForm = TRUE;

            }

            // Cookie login check done.
            $loginCheck = TRUE;

        }

        // Login verification.
        if (isset($_POST['submit'])
            && $_POST['submit'] == 1
            && !empty($_POST['username'])
            && !empty($_POST['password'])) {

            if ($validSession === FALSE) {

                $validSession = $this->session->session_secure_init();

            }

            $username = (string) $_POST['username'];

            $password = (string) $_POST['password'];

            if (!ctype_alpha($username)) {

                $username = preg_replace("/[^a-zA-Z]+/", "", $username);

            }

            if (strlen($username) > 40) {

                $username = substr($username, 0, 39);

            }

            $password = preg_replace("/[^_a-zA-Z0-9]+/", "", $password);

            if (strlen($password) > 40) {

                $password = substr($password, 0, 39);

            }

            // Check credentials.
            if ($this->checkLogin->checkLogin($username, $password)) {

                if ($validSession === TRUE) {

                    //  Check for cookie tampering.
                    if (isset($_SESSION['LOGGEDIN'])) {

                        $validSession = $this->session->session_obliterate();
                        $errorMessage = 3;
                        $postLoginForm = TRUE;

                    } else {

                        setcookie('loggedin', TRUE, time()+ 4200, '/');
                        $_SESSION['LOGGEDIN'] = TRUE;
                        $_SESSION['REMOTE_USER'] = $username;
                        $postLoginForm = FALSE;

                    }

                } else {

                    $validSession = $this->session->session_obliterate();
                    $errorMessage = 3;
                    $postLoginForm = TRUE;

                }

            } else {

                $validSession = $this->session->session_obliterate();
                $errorMessage = 1;
                $postLoginForm = TRUE;

            }

            // Username-password login check done.
            $loginCheck = TRUE;

        }

        // Intercept logout POST.
        if (isset($_POST['logout'])) {

            if ($validSession === FALSE) {

                session_secure_init();

            }

            $validSession = $this->session->session_obliterate();
            $errorMessage = 2;
            $postLoginForm = TRUE;

        }

        // Intercept invalid sessions and redirect to login page.
        if ($loginCheck === TRUE && $validSession === FALSE && $errorMessage === 0) {

            if ($validSession === FALSE) {

                $validSession = $this->session->session_secure_init();
                $validSession = $this->session->session_obliterate();

            }

            $errorMessage = 3;
            $postLoginForm = TRUE;

        }


        $this->data= $this->setData($userMessage,$errorMessage,$postLoginForm);
        $this->viewManager = new TemplateManager($this->data);
        $this->viewManager->loadTemplate();
        $this->viewManager->render();

        ob_end_flush();

        flush();

        exit;


    }

}
