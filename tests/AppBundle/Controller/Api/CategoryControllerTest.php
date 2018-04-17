<?php

namespace Tests\AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testListCategoriesAction()
    {
    	$client = static::createClient();

        $crawler = $client->request('GET', '/api/categories');

        $client->request(
		    'GET',
		    '/api/categories',
		    array(),
		    array(),
		    array(
		        'CONTENT_TYPE'  => 'application/json',
		        'HTTP_X-USERNAME'    => 'rho@email.com',
		        'HTTP_X-PASSWORD'    => 'test',
		    )
		);

		$expected = '[{"id":2,"name":"Comedy"},{"id":1,"name":"Drama"}]';

		$this->assertSame($expected, $client->getResponse()->getContent());
    }
}