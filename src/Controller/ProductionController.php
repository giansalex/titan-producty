<?php

namespace App\Controller;

use App\Entity\Production;
use App\Repository\ProductionRepository;
use App\Services\Ensure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/production")
 */
class ProductionController extends Controller
{
    /**
     * @Route("/", name="production_index", methods="GET", options={"expose": true})
     * @param ProductionRepository $repository
     * @return Response
     */
    public function index(ProductionRepository $repository): Response
    {
        $items = $repository->findBy(['user' => $this->getUser()]);

        return $this->render('production/index.html.twig', ['productions' => $items]);
    }

    /**
     * @Route("/new", name="production_new", methods="GET")
     * @return Response
     */
    public function new(): Response
    {
        return $this->render('production/new.html.twig');
    }

    /**
     * @Route("/{id}", name="production_show", methods="GET")
     * @param int $id
     * @param ProductionRepository $repository
     * @param Ensure $ensure
     * @return Response
     */
    public function show($id, ProductionRepository $repository, Ensure $ensure): Response
    {
        $production = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $ensure->ifNotEmpty($production);

        return $this->render('production/show.html.twig', [
            'production' => $production
        ]);
    }


    /**
     * @Route("/{id}/edit", name="production_edit", methods="GET")
     * @param int $id
     * @return Response
     */
    public function edit($id): Response
    {
        return $this->render('production/edit.html.twig', ['id' => $id]);
    }

    /**
     * @Route("/{id}", name="production_delete", methods="DELETE")
     * @param Request $request
     * @param Production $production
     * @return Response
     */
    public function delete(Request $request, Production $production): Response
    {
        if ($this->isCsrfTokenValid('delete'.$production->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($production);
            $em->flush();
        }

        return $this->redirectToRoute('production_index');
    }
}
