<?php

namespace App\Controller;

use App\Entity\Material;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/material")
 */
class MaterialController extends Controller
{
    /**
     * @Route("/", name="material_index", methods="GET", options={"expose": true})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('material/index.html.twig');
    }

    /**
     * @Route("/new", name="material_new", methods="GET")
     * @return Response
     */
    public function new(): Response
    {
        return $this->render('material/new.html.twig');
    }

    /**
     * @Route("/inventory", name="material_inventory", methods="GET")
     * @return Response
     */
    public function inventory(): Response
    {
        return $this->render('material/inventory.html.twig');
    }

    /**
     * @Route("/order", name="material_order", methods="GET")
     * @return Response
     */
    public function order(): Response
    {
        return $this->render('material/order.html.twig');
    }

    /**
     * @Route("/{id}", name="material_show", methods="GET", options={"expose": true})
     * @param Material $material
     * @return Response
     */
    public function show(Material $material): Response
    {
        return $this->render('material/show.html.twig', ['material' => $material]);
    }

    /**
     * @Route("/{id}/edit", name="material_edit", methods="GET", options={"expose": true})
     * @param int $id
     * @return Response
     */
    public function edit($id): Response
    {
        return $this->render('material/edit.html.twig', ['id' => $id]);
    }

    /**
     * @Route("/{id}", name="material_delete", methods="DELETE")
     * @param Request $request
     * @param Material $material
     * @return Response
     */
    public function delete(Request $request, Material $material): Response
    {
        if ($this->isCsrfTokenValid('delete'.$material->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($material);
            $em->flush();
        }

        return $this->redirectToRoute('material_index');
    }
}
