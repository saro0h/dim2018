<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\User;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

	/**
	 * @Method({"POST"})
	 * @Route("/users", name="create")
	 */
	public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EncoderFactoryInterface $encoderFactory)
	{
		$serialzationContext = DeserializationContext::create();
		$user = $serializer->deserialize(
			$request->getContent(),
			User::class,
			'json',
			$serialzationContext->setGroups(['user_create', 'user'])
		);

		$constraintViolationList = $validator->validate($user);

		if ($constraintViolationList->count() == 0) {

			$encoder = $encoderFactory->getEncoder($user);
			$password = $encoder->encodePassword($user->getPassword(), null);
			$user->setPassword($password);

			$user->setRoles(explode($user->getRoles(), ', '));

			$em = $this->getDoctrine()->getEntityManager();

			$em->persist($user);
			$em->flush();

			return $this->returnResponse('User created', Response::HTTP_CREATED);
		}

		return $this->returnResponse($serializer->serialize($constraintViolationList, 'json'), Response::HTTP_BAD_REQUEST);	
	}
}