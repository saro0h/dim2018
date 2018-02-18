<?php

namespace AppBundle\ShowFinder;

use GuzzleHttp\Client;

class OMDBShowFinder implements ShowFinderInterface
{
	private $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	public function findByName($query)
	{
		$results = $this->client->get('/?apikey=be9bb5cf&type=series&t="walking"');

		dump(\GuzzleHttp\json_decode($results->getBody(), true)); die;
	}

	// Create a private function that transform a OMDB JSON into a Show and Category

	public function getName()
	{
		return 'IMDB API';
	}
}