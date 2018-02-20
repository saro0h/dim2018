<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="api_user_")
 */
class UserController extends Controller
{
	/**
	 * @Method({"GET"})
	 * @Route("/users", name="list")
	 */
	public function listAction(SerializerInterface $serializer)
	{
		$users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
		$serialzationContext = SerializationContext::create();

		return $this->returnResponse(
			$serializer->serialize($users, 'json', $serialzationContext->setGroups(['user'])),
			Response::HTTP_OK
		);
	}

	/**
	 * @Method({"GET"})
	 * @Route("/users/{id}", name="get")
	 */
	public function getAction(User $user, SerializerInterface $serializer)
	{
		$serialzationContext = SerializationContext::create();

		return $this->returnResponse(
			$serializer->serialize($user, 'json', $serialzationContext->setGroups(['user'])),
			Response::HTTP_OK
		);
	}
}