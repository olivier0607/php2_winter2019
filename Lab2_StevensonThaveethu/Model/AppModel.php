<?php
/**
 *
 * Lab 2
 * 
 * Author: Stevenson Thaveethu
 * Date: March 6, 2019
 * 
 * Description: - Concerned with the business logic and the application data.
 *              - Used to perform data validations.
 * 
 * This is the DATA STORE
 * 
 */

class AppModel
{
    
    public function getConnection($getLink = TRUE)
    {

        static $link = NULL;

        if ($link === NULL)
        {

            $link = mysqli_connect('localhost:3307', 'loginuser', 'testpass', 'andrew_session_app');

        }
        elseif ($getLink === FALSE)
        {

            mysqli_close($link);

        }

        return $link;

    }


    protected function getQuote()
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

}