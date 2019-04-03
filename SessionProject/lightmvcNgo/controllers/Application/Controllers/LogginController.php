<?php

namespace Application\Controllers;


use Application\Models\Entity\Users;
use Application\Services\AuthenticationService;
use Application\Services\CrudServiceTrait;
use Ascmvc\AscmvcControllerFactoryInterface;
use Ascmvc\Mvc\AscmvcEventManager;
use Ascmvc\Mvc\Controller;
use Ascmvc\Mvc\AscmvcEvent;
use Pimple\Container;


class LogginController extends Controller implements AscmvcControllerFactoryInterface
{
    use CrudServiceTrait;

    protected $loginCheck = FALSE;

    protected $validSession = FALSE;

    protected $postLoginForm = TRUE;

// Initialize application business and frontend messages.
    protected $errorMessage = 0;

    protected $userMessage = '';


    public static function factory(array &$baseConfig, &$viewObject, Container &$serviceManager, AscmvcEventManager &$eventManager)
    {
        $serviceManager[LogginController::class] = $serviceManager->factory(function ($serviceManager) use ($baseConfig) {
            $em = $serviceManager['dem2'];

            $users = new Users();

            $crudService = new AuthenticationService($users, $em);

            $controller = new LogginController($baseConfig);

            $controller->setCrudService($crudService);

            return $controller;
        });
    }

    /*public function onDispatch(AscmvcEvent $event)
    {
        $array = [
            'firstname' => 'Andrew',
            'lastname' => 'Caya',
            'age' => 42,
        ];

        $response = new Response();
        $response->getBody()->write(json_encode($array));
        $response = $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withAddedHeader('X-Custom-Header', 'it works');

        return $response;
    }*/

    public function onDispatch(AscmvcEvent $event)
    {
        $this->view['saved'] = 0;

        $this->view['error'] = 0;
    }


    protected function readUsers($username = null)
    {
        if ($username == null) {
            return $this->crudService->read();
        } else {
            return $this->crudService->read($username);
        }
    }

    protected function hydrateArray(Users $object)
    {

        $array['username'] = $object->getUsername();
        $array['password'] = $object->getPassword();
        return $array;
    }


    public function indexAction($vars = null)
    {
        if (!isset($this->validSession)) {
            $this->validSession = FALSE;
        } else {
            $this->validSession = TRUE;
        }

        // Check if user is already logged in.
        if (isset($_COOKIE['loggedin'])) {

            if ($this->validSession === FALSE) {

                $this->validSession = $this->crudService->session_secure_init();

            }

            if ($this->validSession === TRUE && isset($_SESSION['LOGGEDIN'])) {

                if (isset($_POST['logout'])) {

                    $this->validSession = $this->crudService->session_obliterate();
                    $this->view['templatefile'] = 'index_index';

                    return $this->view;

                } else {
                    $this->view['templatefile'] = 'form2_index';

                    return $this->view;
                }


            } else {

                $this->validSession = $this->crudService->session_obliterate();

                $this->errorMessage = 3;

                $this->postLoginForm = TRUE;

            }

            // Cookie login check done.
            $this->loginCheck = TRUE;

        }

        // Login verification.
        if (isset($_POST['submit'])
            && $_POST['submit'] == 1
            && !empty($_POST['username'])
            && !empty($_POST['password'])) {

            if ($this->validSession === FALSE) {

                $this->validSession = $this->crudService->session_secure_init();

            }
            $username = $this->crudService->sanitizeString((string)$_POST['username']);

            $password = $this->crudService->sanitizeWithNumber((string)$_POST['password']);


            $results = $this->readUsers();

            if (is_object($results)) {
                $results = [$this->hydrateArray($results)];
            } elseif (is_array($results)) {
                for ($i = 0; $i < count($results); $i++) {
                    $results[$i] = $this->hydrateArray($results[$i]);
                }
            } else {
                $results['nodata'] = 'No results';
            }

            $this->loginCheck = $this->crudService->checkLogin($results, $username, $password);


            if ($this->loginCheck == true) {


                if ($this->validSession === TRUE) {

                    setcookie('loggedin', TRUE, time() + 4200, '/');
                    $_SESSION['LOGGEDIN'] = TRUE;
                    $_SESSION['REMOTE_USER'] = $username;
                    $this->postLoginForm = FALSE;

                } else {

                    $this->validSession = $this->crudService->session_obliterate();
                    $this->errorMessage = 3;
                    $this->postLoginForm = TRUE;

                }

            } else {

                $this->validSession = $this->crudService->session_obliterate();
                $this->errorMessage = 1;
                $this->postLoginForm = TRUE;

            }

            // Username-password login check done.
            $this->loginCheck = TRUE;


        }


        // Intercept invalid sessions and redirect to login page.
        if ($this->loginCheck === TRUE && $this->validSession === FALSE && $this->errorMessage === 0) {

            if ($this->validSession === FALSE) {

                $this->validSession = $this->crudService->session_secure_init();
                $this->validSession = $this->crudService->session_obliterate();

            }

            $this->errorMessage = 3;
            $this->postLoginForm = TRUE;

        }


        // Prepare view output.
        if ($this->postLoginForm === TRUE) {

            switch ($this->errorMessage) {
                case 0:
                    $this->userMessage = 'Please sign in.';
                    break;
                case 1:
                    $this->userMessage = 'Wrong credentials.  <a href="../../../users">Try again</a>.';
                    break;
                case 2:
                    $this->userMessage = 'You are logged out!  <a href="../../../users">You can login again</a>.';
                    break;
                case 3:
                    $this->userMessage = 'Invalid session. <a href="../../../users">Please login again</a>.';
                    break;
            }


            $this->view['templatefile'] = 'form_index';
            echo "<form class=\"form-signin\"><h1 class=\"h3 mb-3 font-weight-normal\">$this->userMessage<h1></form>";
        } else {

            $this->view['bodyjs'] = 1;
            $this->view['templatefile'] = 'index_index';

        }

        return $this->view;

    }


}






