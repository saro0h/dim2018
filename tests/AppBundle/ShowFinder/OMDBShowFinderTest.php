<?php

namespace Tests\AppBundle\ShowFinder;

use AppBundle\Entity\Category;
use AppBundle\Entity\Show;
use AppBundle\Entity\User;
use AppBundle\ShowFinder\OMDBShowFinder;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class OMDBShowFinderTest extends TestCase
{
	public function testOMDBReturnsNoShows()
	{
		$client = $this
		    ->getMockBuilder(Client::class)
		    ->disableOriginalConstructor()
            ->getMock()
        ;

        $results = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $tokenStorage = $this->createMock(TokenStorage::class);

        $results->method('getBody')->willReturn('{"Response":"False","Error":"Series not found!"}');

        $client
            ->method('__call')
            ->with($this->equalTo('get'))
            ->willReturn($results)
        ;


		$omdbShowFinder = new OMDBShowFinder($client, $tokenStorage, '');
		$res = $omdbShowFinder->findByName('My Research');

		$this->assertSame([], $res);
	}

	public function testFindShows()
	{
		$OMDBJson = '{"Title": "Johnny Test","Released": "01 Jan 2005","Genre": "Animation, Action, Adventure","Country": "USA, Canada","Poster": "https:\/\/ia.media-imdb.com\/images\/M\/MV5BYzc3OGZjYWQtZGFkMy00YTNlLWE5NDYtMTRkNTNjODc2MjllXkEyXkFqcGdeQXVyNjExODE1MDc@._V1_SX300.jpg", "Response":"true"}';

		$results = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $results->method('getBody')->willReturn($OMDBJson);


		$client = $this
		    ->getMockBuilder(Client::class)
		    ->disableOriginalConstructor()
            ->getMock()
        ;
		$client
            ->method('__call')
            ->with($this->equalTo('get'))
            ->willReturn($results)
        ;
        $user = new User();
        $token = $this->createMock(UsernamePasswordToken::class);
        $token->method('getUser')->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorage::class);
        $tokenStorage->method('getToken')->willReturn($token);

		$omdbShowFinder = new OMDBShowFinder($client, $tokenStorage, '');
		$res = $omdbShowFinder->findByName('Test');

		$expectedShow = new Show();
		$expectedCategory = new Category();
		$expectedCategory->setName('Animation, Action, Adventure');
		$expectedShow
		   ->setCategory($expectedCategory)
		   ->setName('Johnny Test')
		   ->setAbstract('Not provided.')
		   ->setCountry('USA, Canada')
		   ->setAuthor($user)
		   ->setReleaseDate(new \Datetime('01 Jan 2005'))
		   ->setMainPicture('https://ia.media-imdb.com/images/M/MV5BYzc3OGZjYWQtZGFkMy00YTNlLWE5NDYtMTRkNTNjODc2MjllXkEyXkFqcGdeQXVyNjExODE1MDc@._V1_SX300.jpg')
		   ->setDataSource('OMDB')
	    ;

		$this->assertEquals([$expectedShow], $res);
	}
}