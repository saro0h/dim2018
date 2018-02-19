<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table
 *
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

    /**
     * @ORM\Column
     */
	private $fullname;

	private $roles;

	/**
     * @ORM\Column
     */
	private $password;

	/**
	 * @ORM\Column
	 *
	 * @Assert\Email
	 */
	private $email;

	public function getFullname()
	{
		return $this->fullname;
	}

	public function setFullname($name)
	{
		$this->fullname = $name;
	}

	public function getRoles()
	{
		return ['ROLE_USER'];
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getSalt()
	{
	}

	public function getUsername()
	{
		return $this->email;
	}

	public function setUsername($email)
	{
		$this->email = $email;
	}

	public function eraseCredentials()
	{
	}
}