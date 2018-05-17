<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 13/05/2018
 * Time: 21:51
 */

namespace App\Controller\Api;

use App\Entity\Formula;
use App\Http\BadRequestResponse;
use App\Repository\FormulaRepository;
use App\Services\ModelStateInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/formula", options={"expose": true})
 */
class FormulaApiController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="formula_api_list")
     * @param FormulaRepository $repository
     * @return JsonResponse
     */
    public function list(FormulaRepository $repository): JsonResponse
    {
        $items = $repository->findBy(['user' => $this->getUser()]);

        return $this->json($items);
    }

    /**
     * @Route("/", methods={"POST"}, name="formula_api_add")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ModelStateInterface $validator
     * @param FormulaRepository $repository
     * @return BadRequestResponse|Response
     */
    public function add(
        Request $request,
        SerializerInterface $serializer,
        ModelStateInterface $validator,
        FormulaRepository $repository)
    {
        $formula = $serializer->deserialize(
            $request->getContent(),
            Formula::class,
            'json'
        );

        if (!$validator->valid($formula)) {

            return new BadRequestResponse((string) $validator);
        }

        /**@var $formula Formula */
        $formula->setUser($this->getUser());
        $repository->add($formula);

        return new Response();
    }

    /**
     * @Route("/{id}/material", methods={"GET"}, name="formula_api_material")
     * @param int $id
     * @param FormulaRepository $repository
     * @return JsonResponse
     */
    public function materials(int $id, FormulaRepository $repository)
    {
        $items = $repository->getMaterials($id, $this->getUser());

        return $this->json($items);
    }
}