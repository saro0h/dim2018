<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\File\FileUploader;
use AppBundle\Type\ShowType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="show_")
 */
class ShowController extends Controller
{
    /**
     * @Route("/", name="list")
     */
    public function listAction(Request $request)
    {
        $showRepository = $this->getDoctrine()->getRepository('AppBundle:Show');
        $session = $request->getSession();

        if ($session->has('query_search_shows')) {
            $querySearchShows = $session->get('query_search_shows');
            $shows = $showRepository->findAllByQuery($querySearchShows);

            $request->getSession()->remove('query_search_shows');
        } else {
            $shows = $showRepository->findAll();
        }

        return $this->render('show/list.html.twig', ['shows' => $shows]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createAction(Request $request, FileUploader $fileUploader)
    {
        $show = new Show();
        $form = $this->createForm(ShowType::class, $show, ['validation_groups' => 'create']);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $generatedFileName = $fileUploader->upload($show->getTmpPicture(), $show->getCategory()->getName());

            $show->setMainPicture($generatedFileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            $this->addFlash('success', 'You successfully added a new show!');

            return $this->redirectToRoute('show_list');
        }

        return $this->render('show/create.html.twig', ['showForm' => $form->createView()]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function updateAction(Show $show, Request $request, FileUploader $fileUploader)
    {
        $showForm = $this->createForm(ShowType::class, $show, [
            'validation_groups' => ['update']
        ]);

        $showForm->handleRequest($request);

        if ($showForm->isValid()) {
            if ($show->getTmpPicture() != null) {

                $generatedFileName = $fileUploader->upload($show->getTmpPicture(), $show->getCategory()->getName());

                $show->setMainPicture($generatedFileName);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'You successfully update the show!');

            return $this->redirectToRoute('show_list');
        }

        return $this->render('show/create.html.twig', [
            'showForm' => $showForm->createView(),
            'show' => $show,
        ]);
    }

    public function categoriesAction()
    {
        return $this->render('_includes/categories.html.twig',
            [
               'categories' => ['Web Design', 'HTML', 'Freebies', 'Javascript', 'CSS', 'Tutorials']
            ]
        );
    }

    /**
     * @Route("/search", name="search")
     * @Method({"POST"})
     */
    public function searchAction(Request $request)
    {
        $request->getSession()->set('query_search_shows', $request->request->get('query'));

        return $this->redirectToRoute('show_list');
    }
}