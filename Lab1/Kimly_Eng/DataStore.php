<?php


class DataStore
{
    protected $firstName;
    protected $lastName;
    protected $age;

    function getConnection()
    {
        if (!isset($link)) {
            static $link = NULL;
        }

        if ($link === NULL) {
            $link = mysqli_connect('localhost:3307', 'root', '', 'andrewdb');
        }
        return $link;
    }

    function closeConnection()
    {
        if (!isset($link)) {
            static $link = NULL;
            return FALSE;
        } else {
            mysqli_close($link);
            return TRUE;
        }
    }

    function getQuote()
    {
        return "'";
    }

// SELECT `id`,`firstname`,`lastname` FROM `customers` WHERE x=y
// $where = [key = column name, value = data]
// $andOr = AND | OR
    function getCustomers(array $where = array(), $andOr = 'AND')
    {
        $query = 'SELECT `id`,`firstname`,`lastname`, `age` FROM `users`';
        if ($where) {
            $query .= ' WHERE ';
            foreach ($where as $column => $value) {
                $query .= $column . ' = ' . $this->getQuote() . $value . $this->getQuote() . ' ' . $andOr;
            }
            $query = substr($query, 0, -(strlen($andOr)));
        }
        $link = $this->getConnection();
        $result = mysqli_query($link, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);

//        $myArray = getCustomers(array('id' => '1'));

        closeConnection();
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        $firstrow = $this->getCustomers(array('id' => '1'))[0];
        $this->firstName = $firstrow['firstname'];
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return DataStore
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        $firstrow = $this->getCustomers(array('id' => '1'))[0];
        $this->lastName = $firstrow['lastname'];
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return DataStore
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        $firstrow = $this->getCustomers(array('id' => '1'))[0];
        $this->age = $firstrow['age'];
        return $this->age;
    }

    /**
     * @param int $age
     * @return DataStore
     */
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

}
