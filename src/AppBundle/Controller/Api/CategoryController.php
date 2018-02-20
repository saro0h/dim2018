<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Category;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(name="api_category_")
 */
class CategoryController extends Controller
{
	/**
	 * @Method({"GET"})
	 * @Route("/categories", name="list")
	 */
	public function listAction(SerializerInterface $serializer)
	{
		$categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

		return $this->returnResponse($serializer->serialize($categories, 'json'), Response::HTTP_OK);
	}

	/**
	 * @Method({"GET"})
	 * @Route("/categories/{id}", name="get")
	 */
	public function getAction(Category $category, SerializerInterface $serializer)
	{
		return $this->returnResponse($serializer->serialize($category, 'json'), Response::HTTP_OK);
	}
}