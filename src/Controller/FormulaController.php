<?php

namespace App\Controller;

use App\Repository\FormulaRepository;
use AppBundle\Util\Ensure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formula")
 */
class FormulaController extends Controller
{
    /**
     * @Route("/", name="formula_index", methods="GET", options={"expose": true})
     */
    public function index(FormulaRepository $formulaRepository): Response
    {
        $items = $formulaRepository->findBy(['user' => $this->getUser()]);

        return $this->render('formula/index.html.twig', [
            'formulas' => $items
        ]);
    }

    /**
     * @Route("/new", name="formula_new", methods="GET")
     */
    public function new(): Response
    {
        return $this->render('formula/new.html.twig');
    }

    /**
     * @Route("/{id}", name="formula_show", methods="GET")
     */
    public function show($id, FormulaRepository $repository, Ensure $ensure): Response
    {
        $formula = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $ensure->ifNotEmpty($formula);

        return $this->render('formula/show.html.twig', [
            'formula' => $formula
        ]);
    }

    /**
     * @Route("/{id}/edit", name="formula_edit", methods="GET")
     */
    public function edit(): Response
    {
        return $this->render('formula/edit.html.twig');
    }
}
