<?php

namespace AppBundle\ShowFinder;

use AppBundle\Entity\Category;
use AppBundle\Entity\Show;
use GuzzleHttp\Client;

class OMDBShowFinder implements ShowFinderInterface
{
	private $client;

	private $apiKey;

	public function __construct(Client $client, $apiKey)
	{
		$this->client = $client;
		$this->apiKey = $apiKey;
	}

	/**
	 * Find a show by a string.
	 *
	 * @param String $query
	 *
	 * @return Array $shows
	 */
	public function findByName($query)
	{
		$results = $this->client->get('/?apikey='.$this->apiKey.'&type=series&t="'.$query.'"');
		$json = \GuzzleHttp\json_decode($results->getBody(), true);

		if ($json['Response'] == 'False' && $json['Error'] == 'Series not found!') {
			return [];
		}

		return $this->convertToShow();
	}

	/**
	 * Create a private function that transform a OMDB JSON into a Show and Category
	 *
	 * @param String $json
	 *
	 * Show[] $shows
	 */ 
	private function convertToShow($json)
	{
		$category = new Category();
		$category->setName($json['Genre']);

		$shows = [];
		$show = new Show();
		$show
			->setName($json['Title'])
			->setDataSource(Show::DATA_SOURCE_OMDB)
			->setAbstract('Not provided.')
			->setCountry($json['Country'])
			->setAuthor('TO DO when the authentication!')
			->setReleaseDate(new \DateTime($json['Released']))
			->setMainPicture($json['Poster'])
			->setCategory($category)
		;

		$shows[] = $show;

		return $shows;
	}

	public function getName()
	{
		return 'IMDB API';
	}
}