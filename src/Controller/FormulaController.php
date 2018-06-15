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
     * @param Request $request
     * @param FormulaRepository $repository
     * @return Response
     */
    public function index(Request $request, FormulaRepository $repository): Response
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
     * @Route("/{id}/edit", name="formula_edit", methods="GET")
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
