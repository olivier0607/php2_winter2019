<?php

class DataStore
{

    public function getConnection($getLink = TRUE)
    {
        static $link = NULL;

        if ($link === NULL) {

            $link = mysqli_connect('localhost:3307', 'loginuser', 'testpass', 'login');

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


    public function checkLogin($username, $password)
    {

        $query = 'SELECT `username`, `password` FROM `users` WHERE `username` LIKE ' . $this->getQuote() . $username . $this->getQuote();

        $values = $this->queryResults($query);

        $passwordVerified = password_verify($password, $values['password']);

        return $passwordVerified;

    }

}