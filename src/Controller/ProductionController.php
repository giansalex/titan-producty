<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductionController extends Controller
{
    /**
     * @Route("/production", name="production_index")
     */
    public function index()
    {
        return $this->render('production/index.html.twig', [
            'controller_name' => 'ProductionController',
        ]);
    }
}
