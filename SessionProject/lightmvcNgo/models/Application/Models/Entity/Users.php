<?php

namespace Application\Models\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity("Application\Models\Entity\Users")
 * @ORM\Entity(repositoryClass="Application\Models\Repository\UsersRepository")
 * @ORM\Table("users")
 */
class Users
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=5)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=60, name="username")
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255, name="password")
     */
    protected $password;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }



    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $username
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }


}
