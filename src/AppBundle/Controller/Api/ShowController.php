<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Show;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="api_show_")
 */
class ShowController extends Controller
{
	/**
	 * @Method({"GET"})
	 * @Route("/shows", name="list")
	 */
	public function listAction(SerializerInterface $serializer)
	{
		$shows = $this->getDoctrine()->getRepository('AppBundle:Show')->findAll();

		$serialzationContext = SerializationContext::create();

		return $this->returnResponse(
			$serializer->serialize($shows, 'json', $serialzationContext->setGroups(['show'])),
			Response::HTTP_OK
		);
	}

	/**
	 * @Method({"GET"})
	 * @Route("/shows/{id}", name="get")
	 */
	public function getAction(Show $show, SerializerInterface $serializer)
	{
		$serialzationContext = SerializationContext::create();

		return $this->returnResponse(
			$serializer->serialize($show, 'json', $serialzationContext->setGroups(['show'])), 
			Response::HTTP_OK
			);
	}
}