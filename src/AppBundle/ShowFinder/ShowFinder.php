<?php

namespace AppBundle\ShowFinder;

class ShowFinder
{
	private $finders;

	public function searchByName($query)
	{
		$tmp = [];

		foreach ($this->finders as $finder) {
			$tmp[$finder->getName()] = $finder->findByName($query);
		}

		dump($tmp);
		die;

		return $results;
	}

	public function addFinder(ShowFinderInterface $finder)
	{
		$this->finders[] = $finder;
	}
}