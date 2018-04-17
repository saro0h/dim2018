<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
	public function testLogin()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/login');


		$this->assertContains('Welcome', $crawler->filter('h1')->text());

		$form = $crawler->selectButton('Connect')->form();
		$crawler = $client->submit(
			$form,
			[
				'email' => 'rho@email.com',
				'password' => 'test'
			]
		);

		$crawler = $client->followRedirect();

		$this->assertContains('List of shows', $crawler->filter('h1')->text());
		
	}
}