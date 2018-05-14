<?php

namespace App\Controller;

use App\Entity\Production;
use App\Form\ProductionType;
use App\Repository\ProductionRepository;
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
     * @Route("/", name="production_index", methods="GET")
     * @param ProductionRepository $productionRepository
     * @return Response
     */
    public function index(ProductionRepository $productionRepository): Response
    {
        return $this->render('production/index.html.twig', ['productions' => $productionRepository->findAll()]);
    }

    /**
     * @Route("/new", name="production_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $production = new Production();

        return $this->render('production/new.html.twig', [
            'production' => $production,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="production_edit", methods="GET|POST")
     */
    public function edit(Request $request, Production $production): Response
    {
        return $this->render('production/edit.html.twig', [
            'production' => $production,
        ]);
    }
}
