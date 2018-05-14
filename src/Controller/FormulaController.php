<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FormulaController extends Controller
{
    /**
     * @Route("/formula", name="formula_index")
     */
    public function index()
    {
        return $this->render('formula/index.html.twig', [
            'controller_name' => 'FormulaController',
        ]);
    }
}
