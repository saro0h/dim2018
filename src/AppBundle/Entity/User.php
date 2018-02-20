<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @ORM\Column(type="json_array")
     */
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

	/**
	 * @ORM\OneToMany(targetEntity="Show", mappedBy="author")
	 */
	private $shows;

	public function __construct()
	{
		$this->shows = new ArrayCollection();
	}

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
		return $this->roles;
	}

	public function setRoles($roles)
	{
		$this->roles = $roles;
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

	public function addShow(Show $show)
	{
		if (!$this->shows->contains($show)){
			$this->shows->add($show);
		}
	}

	public function removeShow(Show $show)
	{
		$this->shows->remove($show);
	}

	public function getShows()
	{
		return $this->shows;
	}
}