<?php

namespace App\Controller;

use App\Entity\HistoryType;
use App\Entity\Product;
use App\Repository\HistoryRepository;
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
     * @Route("/", name="product_index", methods="GET", options={"expose": true})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig');
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
     * @Route("/inventory", name="product_inventory", methods="GET")
     * @return Response
     */
    public function inventory(): Response
    {
        return $this->render('product/inventory.html.twig');
    }

    /**
     * @Route("/sale", name="product_sale", methods="GET")
     * @return Response
     */
    public function sale(): Response
    {
        return $this->render('product/sale.html.twig');
    }

    /**
     * @Route("/{id}", name="product_show", methods="GET", options={"expose": true})
     * @param int $id
     * @param ProductRepository $repository
     * @param HistoryRepository $historyRepository
     * @param Ensure $ensure
     * @return Response
     */
    public function show($id, ProductRepository $repository, HistoryRepository $historyRepository, Ensure $ensure): Response
    {
        $product = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $ensure->ifNotEmpty($product);

        $items = $historyRepository->getQueryMaterialByUser($this->getUser())
            ->andWhere('h.type = ?1 AND h.itemId = ?2')
            ->setParameter(1, HistoryType::PRODUCT)
            ->setParameter(2, $id)
            ->getQuery()
            ->getResult();

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'histories' => $items
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET", options={"expose": true})
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
