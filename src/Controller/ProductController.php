<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Services\Ensure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="product_index", methods="GET|POST", options={"expose": true})
     * @param Request $request
     * @param ProductRepository $repository
     * @return Response
     */
    public function index(Request $request, ProductRepository $repository): Response
    {
        if ($request->request->has('search')) {
            $search = $request->request->get('search');
            $items = $repository->createQueryBuilder('c')
                ->select('c')
                ->where('c.user = ?0 and c.name LIKE ?1')
                ->setParameters([$this->getUser(), '%'.$search.'%'])
                ->getQuery()
                ->getResult();
        } else {
            $items = $repository->findBy(['user' => $this->getUser()]);
        }

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
     * @Route("/{id}", name="product_show", methods="GET")
     * @param int $id
     * @param ProductRepository $repository
     * @param Ensure $ensure
     * @return Response
     */
    public function show($id, ProductRepository $repository, Ensure $ensure): Response
    {
        $product = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $ensure->ifNotEmpty($product);

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET")
     * @param int $id
     * @return Response
     */
    public function edit($id): Response
    {
        return $this->render('product/edit.html.twig', ['id' => $id]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods="DELETE")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
