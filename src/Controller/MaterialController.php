<?php

namespace App\Controller;

use App\Entity\Material;
use App\Repository\MaterialRepository;
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
     * @param MaterialRepository $materialRepository
     * @return Response
     */
    public function index(MaterialRepository $materialRepository): Response
    {
        $items = $materialRepository->findBy(['user' => $this->getUser()]);

        return $this->render('material/index.html.twig', ['materials' => $items]);
    }

    /**
     * @Route("/new", name="material_new", methods="GET|POST")
     * @return Response
     */
    public function new(): Response
    {
        return $this->render('material/new.html.twig');
    }

    /**
     * @Route("/{id}", name="material_show", methods="GET")
     */
    public function show(Material $material): Response
    {
        return $this->render('material/show.html.twig', ['material' => $material]);
    }

    /**
     * @Route("/{id}/edit", name="material_edit", methods="GET|POST")
     */
    public function edit($id): Response
    {
        return $this->render('material/edit.html.twig', ['id' => $id]);
    }

    /**
     * @Route("/{id}", name="material_delete", methods="DELETE")
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
