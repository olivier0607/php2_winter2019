<?php

namespace Application\Services;

use Application\Models\Entity\Users;
use Application\Models\Traits\DoctrineTrait;
use Application\Models\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class AuthenticationService
{


    use DoctrineTrait;

    protected $users;

    protected $usersRepository;

    public function __construct(Users $users, EntityManager $em)
    {
        $this->users = $users;

        $this->em = $em;

        $this->usersRepository = new usersRepository(
            $this->em,
            new ClassMetaData('Application\Models\Entity\Users')
        );
    }


    protected function getQuote()
    {
        return "'";
    }

    public function checkLogin(array $results, string $username_post, string $password_post)
    {

        foreach ($results as $value) {
            if ($value['username'] == $username_post) {
                $result = $value['username'];

                $password = $value['password'];

            }

        };
        if (!isset($result)) {
            return FALSE;
        }

        $passwordVerified = password_verify($password_post, $password);


        return $passwordVerified;
    }

    public function session_obliterate()
    {

        $_SESSION = array();
        setcookie(session_name(), '', time() - 3600, '/');
        setcookie('loggedin', '', time() - 3600, '/');
        session_destroy();   // Destroy session data in storage.
        session_unset();     // Unset $_SESSION variable for the runtime.
        $this->validSession = FALSE;
        return $this->validSession;

    }

    public function session_secure_init()
    {

        session_write_close();
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

            $this->validSession = FALSE;

        }

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


    public function create(array $array)
    {
        try {
            $this->usersRepository->save($array);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }


    public function read(string $username = null)
    {
        try {
            if (isset($username)) {
                $results = $this->getEm()->find(Users::class, $username);
            } else {
                $results = $this->usersRepository->findAll();
            }
        } catch (\Exception $e) {
            return false;
        }

        return $results;
    }


    public function update(array $array)
    {
        try {
            if (isset($array['id'])) {
                $users = $this->getEm()->find(Users::class, $array['id']);
                $this->usersRepository->save($array, $users);
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function delete(int $id)
    {
        try {
            $users = $this->getEm()->find(Users::class, $id);
            $this->usersRepository->delete($users);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function sanitizeWithNumber(string $number)
    {

        $number = preg_replace("/[^_a-zA-Z0-9]+/", "", $number);

        if (strlen($number > 40)) {

            $number = substr($number, 0, 39);

        }
        return $number;


    }

    public function sanitizeString(string $name)
    {

        if (!ctype_alpha($name)) {

            $name = preg_replace("/[^a-zA-Z]+/", "", $name);

        }

        if (strlen($name) > 40) {

            $name = substr($name, 0, 39);

        }

        return $name;

    }


}
