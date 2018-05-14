<?php

namespace App\Controller;

use App\Entity\Formula;
use App\Form\FormulaType;
use App\Repository\FormulaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formula")
 */
class FormulaController extends Controller
{
    /**
     * @Route("/", name="formula_index", methods="GET")
     * @param FormulaRepository $formulaRepository
     * @return Response
     */
    public function index(FormulaRepository $formulaRepository): Response
    {
        return $this->render('formula/index.html.twig', ['formulas' => $formulaRepository->findAll()]);
    }

    /**
     * @Route("/new", name="formula_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $formula = new Formula();

        return $this->render('formula/new.html.twig', [
            'formula' => $formula,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="formula_edit", methods="GET|POST")
     */
    public function edit(Request $request, Formula $formula): Response
    {
        return $this->render('formula/edit.html.twig', [
            'formula' => $formula
        ]);
    }
}
