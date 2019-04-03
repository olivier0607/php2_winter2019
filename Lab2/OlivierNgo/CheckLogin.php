<?php
/**
 * Created by PhpStorm.
 * User: olivier
 * Date: 2019-03-18
 * Time: 11:41 AM
 */

class CheckLogin

{
    protected $dataStorage2;
    public function __construct(DataStorage $dataStorage)
    {
        $this->dataStorage2 = $dataStorage;

    }


    protected function getQuote()
    {
        return "'";
    }


    public function checkLogin($username, $password)
    {

        $query = 'SELECT `username`, `password` FROM `users` WHERE `username` LIKE ' . $this->getQuote() . $username . $this->getQuote();

        $values = $this->dataStorage2->queryResults($query);

        $passwordVerified = password_verify($password, $values['password']);

        return $passwordVerified;
    }
}
