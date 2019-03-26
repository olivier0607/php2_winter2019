<?php
/**
 *
 * Lab 2
 * 
 * Author: Stevenson Thaveethu
 * Date: March 6, 2019
 * 
 * Description: Deals with the user requests for resources from the server.
 * 
 * This is the INDEX CONTROLLER
 *
 */

class AppController
{
    
    protected $appModel;
    
    protected $appView;

    protected $appSessionManager;

    protected $data = array();


    public function __construct(AppModel $appModel, AppSessionManager $appSessionManager)
    {

        $this->appModel = $appModel;
        $this->appSessionManager = $appSessionManager;

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
    

    public function webActions()
    {
        
        //  Output buffer. 
        
        ob_start();
        
        //  Flags.
        
        $loginCheck = FALSE;
        
        $validSession = FALSE;

        $postLoginForm = TRUE;
        
        //  Initialize app business and frontend messages.
        
        $errorMessage = 0;
        
        $userMessage = '';
        
        //  Check if user is already logged in. 
        
        if (isset($_COOKIE['loggedin']))
        {
            
            if ($validSession === FALSE)
            {
                
                $validSession = $this->appSessionManager->session_secure_init();
                
            }
            
            //  Check for cookie tampering.
            
            if ($validSession === TRUE && isset($_SESSION['LOGGEDIN']))
            {
                
                $postLoginForm = FALSE;
                
            }
            else
            {
                
                $validSession = $this->appSessionManager->session_obliterate();
                
                $errorMessage = 3;
				
				$postLoginForm = TRUE;
                
            }
            
            //  Cookie login check done.
            
            $loginCheck = TRUE;
            
        }
        
        
        //  Login verification.
        
        if (isset($_POST['submit']) && $_POST['submit'] == 1 
									&& !empty($_POST['username']) 
									&& !empty($_POST['password']))
        {
            
            if ($validSession === FALSE)
            {
                
                $validSession = $this->appSessionManager->session_secure_init();
                
            }
            
            $username = (string) $_POST['username'];
            
            $password = (string) $_POST['password'];
            
            if (!ctype_alpha($username))
            {
                
                $username = preg_replace("/[^a-zA-Z]+/", "", $username);
                
            }
            
            if (strlen($username) > 40)
            {
                
                $username = substr($username, 0, 39);
                
            }
            
            $password = preg_replace("/[^_a-zA-Z0-9]+/", "", $password);
            
            if (strlen($password) > 40)
            {
            
                $password = substr($password, 0, 39);
            
            }
            
            
            //  Check credentials.
            
            if ($this->appSessionManager->checkLogin($username, $password))
            {
                
                if ($validSession === TRUE)
                {
                    
                    //  Check for cookie tampering.
                    
                    if (isset($_SESSION['LOGGEDIN']))
                    {
                        
                        $validSession = $this->appSessionManager->session_obliterate();
                        
                        $errorMessage = 3;
                        
                        $postLoginForm = TRUE;
                        
                    }
                    else
                    {
                        
                        setcookie('loggedin', TRUE, time() + 4200, '/');
                        
                        $_SESSION['LOGGEDIN'] = TRUE;
                        
                        $_SESSION['REMOTE_USER'] = $username;
                        
                        $postLoginForm = FALSE;
                        
                    }
                    
                }
                else
                {
                    
                    $validSession = $this->appSessionManager->session_obliterate();
                    
                    $errorMessage = 3;
                    
                    $postLoginForm = TRUE;
                    
                }
                
            }
            else
            {
                
                $validSession = $this->appSessionManager->session_obliterate();
                
                $errorMessage = 1;
                
                $postLoginForm = TRUE;
                
            }
            
            //  Username-password login check done.
            
            $loginCheck = TRUE;
            
        }
        
        //  Intercept logout POST.
        
        if (isset($_POST['logout']))
        {
            
            if ($validSession === FALSE)
            {
                
                session_secure_init();
                
            }
            
            $validSession = $this->appSessionManager->session_obliterate();
            
            $errorMessage = 2;
            
            $postLoginForm = TRUE;
            
        }
        
        //  Intercept invalid sessions and redirect to login page.
        
        if ($loginCheck === TRUE && $validSession === FALSE && $errorMessage === 0)
        {
            
            if ($validSession === FALSE)
            {
                
                $validSession = $this->appSessionManager->session_secure_init();
                
                $validSession = $this->appSessionManager->session_obliterate();
                
            }
            
            $errorMessage  = 3;
            
            $postLoginForm = TRUE;
            
        }
        
        
        $this->data= $this->setData($userMessage,$errorMessage,$postLoginForm);

        $this->appView= new AppView($this->data);

        $this->appView->loadTemplate();

        $this->appView->render();

        ob_end_flush();

        flush();

        exit;
        
    }
    
}
