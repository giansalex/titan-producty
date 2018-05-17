<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="product_index", methods="GET")
     * @param ProductRepository $repository
     * @return Response
     */
    public function index(ProductRepository $repository): Response
    {
        $items = $repository->findBy(['user' => $this->getUser()]);

        return $this->render('product/index.html.twig', ['products' => $items]);
    }

    /**
     * @Route("/new", name="product_new", methods="GET")
     * @return Response
     */
    public function new(): Response
    {
        return $this->render('product/new.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET")
     */
    public function edit(): Response
    {
        return $this->render('product/edit.html.twig');
    }
}
