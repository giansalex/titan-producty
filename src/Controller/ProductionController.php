<?php

namespace App\Controller;

use App\Repository\ProductionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/{id}/edit", name="production_edit", methods="GET")
     */
    public function edit(): Response
    {
        return $this->render('production/edit.html.twig');
    }
}
