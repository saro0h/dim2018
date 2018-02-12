<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class User
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

	public function getFullname()
	{
		return $this->fullname;
	}

	public function setFullname($name)
	{
		$this->fullname = $name;
	}
}