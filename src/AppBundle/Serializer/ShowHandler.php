<?php

namespace AppBundle\Serializer;

use AppBundle\Entity\Show;
use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Handler\SubscribingHandlerInterface;

class ShowHandler implements SubscribingHandlerInterface
{
	private $doctrine;

	public function __construct(ManagerRegistry $doctrine)
	{
		$this->doctrine = $doctrine;
	}


	public static function getSubscribingMethods()
	{
		return [
			[
				'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
				'format' => 'json',
				'type' => 'AppBundle\Entity\Show',
				'method' => 'deserialize'
			]
		];
	}

	public function deserialize(JsonDeserializationVisitor $visitor, $data)
	{
		$em = $this->doctrine->getManager();
		$show = new Show();

		if (!$category = $em->getRepository('AppBundle:Category')->findOneById($data['category']['id'])) {
			throw new \LogicException('The category does not exist');
		}

		
		$show
		    ->setCategory($category)
		    ->setName($data['name'])
		    ->setAbstract($data['abstract'])
		    ->setCountry($data['country'])
		    ->setReleaseDate(new \DateTime($data['release_date']))
		    ->setMainPicture($data['main_picture'])
		;

		return $show;
	}
}