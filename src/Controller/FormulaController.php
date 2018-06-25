<?php

namespace App\Controller;

use App\Entity\Formula;
use App\Repository\FormulaRepository;
use App\Services\Ensure;
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
     * @Route("/", name="formula_index", methods="GET|POST", options={"expose": true})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('formula/index.html.twig');
    }

    /**
     * @Route("/new", name="formula_new", methods="GET")
     */
    public function new(): Response
    {
        return $this->render('formula/new.html.twig');
    }

    /**
     * @Route("/{id}", name="formula_show", methods="GET", options={"expose": true})
     * @param $id
     * @param FormulaRepository $repository
     * @param Ensure $ensure
     * @return Response
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
     * @Route("/{id}/edit", name="formula_edit", methods="GET", options={"expose": true})
     * @param int $id
     * @return Response
     */
    public function edit($id): Response
    {
        return $this->render('formula/edit.html.twig', ['id' => $id]);
    }

    /**
     * @Route("/{id}", name="formula_delete", methods="DELETE")
     * @param Request $request
     * @param Formula $formula
     * @return Response
     */
    public function delete(Request $request, Formula $formula): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formula->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($formula);
            $em->flush();
        }

        return $this->redirectToRoute('formula_index');
    }
}
