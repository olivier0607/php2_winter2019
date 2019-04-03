<?php
/**
 * Created by PhpStorm.
 * User: Evgeniya
 * Date: 2019-03-06
 * Time: 11:49 PM
 */

class SessionService
{
    // Backend data store
    protected $dataStore;

    // Initialize application business and frontend messages.
    protected $errorMessage = 0;
    protected $userMessage = 'Please sign in';
    protected $session="";


    // Set flags.
    protected $loginCheck = FALSE;
    protected $validSession = FALSE;
    protected $postLoginForm = TRUE;

    public function __construct(DataStore $dataStore)
    {
        $this->dataStore = $dataStore;
    }

    /**
     * @return int
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return string
     */
    public function getUserMessage()
    {
        return $this->userMessage;
    }

    public function getPostLoginForm() {
        return $this->postLoginForm;
    }

    public function loginVerification() {

        // Check if user is already logged in.
        if (isset($_COOKIE['loggedin'])) {

            if ($this->validSession === FALSE) {

                $this->validSession = $this->session_secure_init();
            }

            //  Check for cookie tampering.
            if ($this->validSession === TRUE && isset($_SESSION['LOGGEDIN'])) {
                $this->postLoginForm = FALSE;
            } else {
                $this->validSession = $this->session_obliterate();
                $this->errorMessage = 3;
                $this->postLoginForm = TRUE;
            }

            // Cookie login check done.
            $this->loginCheck = TRUE;
        }

        if (isset($_POST['submit'])
            && !empty($_POST['username'])
            && !empty($_POST['password'])
            && isset($_POST['repeatpassword'])
            && !empty($_POST['repeatpassword'])) {

            // receive all input values from the form
            // DANGER! - values must be filtered, validated and sanitized!
            $username = $_POST['username'];
            $password = $_POST['password'];
            $passwordrepeat = $_POST['repeatpassword'];

            // form validation: ensure that the form is correctly filled ...
            // by adding (array_push()) corresponding error unto $errors array
            if (empty($name)) {
                $this->userMessage = '<span style="color: firebrick;text-align:center;">Username is required</span>';
            }
            if (empty($password)) {
                $this->userMessage = '<span style="color: firebrick;text-align:center;">Password is required</span>';
            }
            if ($password != $passwordrepeat) {
                $this->userMessage = '<span style="color: firebrick;text-align:center;">The two passwords do not match</span>';
            }

            $errorMessage = $this->dataStore->registerUser($username, $password);

            $this->userMessage = isset($errorMessage) ? '<span style="color: firebrick;text-align:center;">'. $errorMessage .'</span>' : '';

            return;
        }

        // Login verification.
        if (isset($_POST['submit'])
            && $_POST['submit'] == 1
            && !empty($_POST['username'])
            && !empty($_POST['password'])) {

            if ($this->validSession === FALSE) {

                $this->validSession = $this->session_secure_init();
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
            if ($this->dataStore->checkLogin($username, $password)) {

                if ($this->validSession === TRUE) {

                    //  Check for cookie tampering.
                    if (isset($_SESSION['LOGGEDIN'])) {

                        $this->validSession = $this->session_obliterate();
                        $this->errorMessage = 3;
                        $this->postLoginForm = TRUE;

                    } else {

                        setcookie('loggedin', TRUE, time()+ 4200, '/');
                        $_SESSION['LOGGEDIN'] = TRUE;
                        $_SESSION['REMOTE_USER'] = $username;
                        $this->postLoginForm = FALSE;
                        $this->session =  $_SESSION['REMOTE_USER'];

                    }

                } else {

                    $this->validSession = $this->session_obliterate();
                    $this->errorMessage = 3;
                    $this->postLoginForm = TRUE;
                }

            } else {

                $this->validSession = $this->session_obliterate();
                $this->errorMessage = 1;
                $this->postLoginForm = TRUE;
            }

            // Username-password login check done.
            $this->loginCheck = TRUE;
        }

        // Intercept logout POST.
        if (isset($_POST['logout'])) {

            if ($this->validSession === FALSE) {

                $this->session_secure_init();
            }

            $this->validSession = $this->session_obliterate();
            $this->errorMessage = 2;
            $this->postLoginForm = TRUE;
        }

        // Intercept invalid sessions and redirect to login page.
        if ($this->loginCheck === TRUE && $this->validSession === FALSE && $this->errorMessage === 0) {

            if ($this->validSession === FALSE) {

                $this->validSession = $this->session_secure_init();
                $this->validSession = $this->session_obliterate();
            }

            $this->errorMessage = 3;
            $this->postLoginForm = TRUE;
        }

        // Prepare view output.
        if ($this->postLoginForm === TRUE) {

            switch ($this->errorMessage) {
                case 0:
                    $this->userMessage = 'Please sign in';
                    break;
                case 1:
                    $this->userMessage = 'Wrong credentials!  <a href="index.php">Try again</a>.';

                    break;
                case 2:
                    $this->userMessage = 'You are logged out!  <a href="index.php">You can login again</a>.';
                    break;
                case 3:
                    $this->userMessage = 'Invalid session <a href="index.php">Please login again</a>.';
                    break;
            }
        }
    }

    public function session_obliterate()
    {

        $_SESSION = array();
        setcookie(session_name(),'', time() - 3600, '/');
        setcookie('loggedin', '', time() - 3600, '/');
        session_destroy();   // Destroy session data in storage.
        session_unset();     // Unset $_SESSION variable for the runtime.
        $this->validSession = FALSE;
        return $this->validSession;

    }

    public function session_secure_init()
    {
        session_set_cookie_params(4200);

        $this->validSession = TRUE;

        if (!defined('OURUNIQUEKEY')) {

            define('OURUNIQUEKEY', 'phpi');

        }

        // Avoid session prediction.
        $sessionname = OURUNIQUEKEY;

        if (session_name() != $sessionname) {

            session_name($sessionname);

        } else {

            session_name();

        }

        // Start session.
        session_start();

        if ((!isset($_COOKIE['loggedin']) && isset($_SESSION['LOGGEDIN']))
            ^ (isset($_COOKIE['loggedin']) && !isset($_SESSION['LOGGEDIN']))) {

            $this->validSession = FALSE;    }

        if ($this->validSession == TRUE) {

            // Avoid session fixation.
            if (!isset($_SESSION['INITIATED'])) {

                session_regenerate_id();
                $_SESSION['INITIATED'] = TRUE;
            }

            if (!isset($_SESSION['CREATED'])) {

                $_SESSION['CREATED'] = time();
            }

            if (time() - $_SESSION['CREATED'] > 3600) {

                // Session started more than 60 minutes ago.
                session_regenerate_id();    // Change session ID for the current session an invalidate old session ID.
                $_SESSION['CREATED'] = time();  // Update creation time.

            }

            // Avoid session hijacking.
            $useragent = $_SERVER['HTTP_USER_AGENT'];

            $useragent .= OURUNIQUEKEY;

            if (isset($_SESSION['HTTP_USER_AGENT'])) {

                if ($_SESSION['HTTP_USER_AGENT'] != md5($useragent)) {

                    $this->validSession = FALSE;
                }

            } else {

                $_SESSION['HTTP_USER_AGENT'] = md5($useragent);

            }

            // Avoid session fixation in case of an inactive session.
            if ($this->validSession == TRUE && isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > 3600) {

                // Last request was more than 60 minutes ago.
                $this->validSession = FALSE;

            } else {

                $_SESSION['LAST_ACTIVITY'] = time(); // Update last activity timestamp.
            }
        }
        return $this->validSession;
    }
}