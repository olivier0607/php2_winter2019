<?php

class DataStore
{
    /* Login App */

    // Thanks to Doug for the 'getConnection' and 'getQuote' functions.
    public function getConnection($getLink = TRUE)
    {

        $link = NULL;

        if ($link === NULL) {

            $link = mysqli_connect('localhost', 'root', '', 'evgeniyadb_login');


        } elseif ($getLink === FALSE) {

            mysqli_close($link);

        }

        return $link;

    }

    public function getQuote()
    {

        return "'";

    }

    public function queryResults($query)
    {

        $link = $this->getConnection();

        $result = mysqli_query($link, $query);

        $values = mysqli_fetch_assoc($result);

        $this->getConnection(FALSE);

        return $values;

    }

    // SELECT `username`, `password` FROM `users` WHERE `username` LIKE $username;
    public function checkLogin($username, $password)
    {

        $query = 'SELECT `username`, `password` FROM `logins` WHERE `username` LIKE ' . $this->getQuote() . $username . $this->getQuote();

        $values = $this->queryResults($query);

        $passwordVerified = password_verify($password, $values['password']);

        return $passwordVerified;

    }

    // REGISTER NEW USER
    public function registerUser($username, $password)
    {
        // first check the database to make sure
        // a user does not already exist with the same username and/or email
        $query = 'SELECT `username`, `password` FROM `logins` WHERE `username` LIKE ' . $this->getQuote() . $username . $this->getQuote();

        $user = $this->queryResults($query);

        if ($user) { // if user exists
            if ($user['username'] === $username) {
                return 'Username already exists';
            }
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = 'INSERT INTO `logins` (`username`, `password`) VALUES (' . $this->getQuote() . $username . $this->getQuote() . ', ' . $this->getQuote() . $password . $this->getQuote() . ')';

        $this->queryResults($query);

        return;
    }

}

