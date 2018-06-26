<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 13/05/2018
 * Time: 21:51
 */

namespace App\Controller\Api;

use App\Dto\FormulaDto;
use App\Entity\Formula;
use App\Http\BadRequestResponse;
use App\Repository\FormulaRepository;
use App\Services\Ensure;
use App\Services\Mapper;
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
     * @param Mapper $mapper
     * @return JsonResponse
     */
    public function list(FormulaRepository $repository, Mapper $mapper): JsonResponse
    {
        $items = $repository->findBy(['user' => $this->getUser()]);
        $dtos = $mapper->mapArray($items, FormulaDto::class);

        return $this->json($dtos);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="formula_api_get")
     * @param int $id
     * @param FormulaRepository $repository
     * @param Mapper $mapper
     * @return JsonResponse
     */
    public function getItem($id, FormulaRepository $repository, Mapper $mapper)
    {
        $formula = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $dto = $mapper->map($formula, FormulaDto::class);

        return $this->json($dto);
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
        $formula = $this->getFormula($request, $serializer);

        if (!$validator->valid($formula)) {

            return new BadRequestResponse((string) $validator);
        }

        /**@var $formula Formula */
        $formula->setUser($this->getUser());
        $repository->add($formula);

        return new Response();
    }

    /**
     * @Route("/{id}", methods={"PUT"}, name="formula_api_edit")
     * @param int $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ModelStateInterface $validator
     * @param FormulaRepository $repository
     * @param Mapper $mapper
     * @param Ensure $ensure
     * @return BadRequestResponse|Response
     */
    public function edit(
        $id,
        Request $request,
        SerializerInterface $serializer,
        ModelStateInterface $validator,
        FormulaRepository $repository,
        Mapper $mapper,
        Ensure $ensure)
    {
        $formula = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $ensure->ifNotEmpty($formula);

        /**@var $newFormula Formula */
        $newFormula = $this->getFormula($request, $serializer);
        $mapper->mapToObject($newFormula, $formula);

        if (!$validator->valid($formula)) {

            return new BadRequestResponse((string) $validator);
        }

        $repository->edit($newFormula, $formula);

        return new Response();
    }

    /**
     * @Route("/{id}", methods={"DELETE"}, name="formula_api_remove")
     * @param int $id
     * @param FormulaRepository $repository
     * @return Response
     */
    public function remove($id, FormulaRepository $repository): Response
    {
        $formula = $repository->findBy(['id' => $id, 'user' => $this->getUser()]);

        if (empty($formula)) {
            $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($formula);
        $em->flush();

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

    /**
     * @Route("/{id}/duplicate", methods={"POST"}, name="formula_api_duplicate")
     * @param Formula $formula
     * @param Mapper $mapper
     * @return JsonResponse
     */
    public function duplicate(Formula $formula, Mapper $mapper)
    {
        $newFormula = clone $formula;
        $newFormula->setName($newFormula->getName().' - copia');

        $em = $this->getDoctrine()->getManager();
        $em->persist($newFormula);
        $em->flush();

        return new JsonResponse($mapper->map($newFormula, FormulaDto::class));
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return object
     */
    private function getFormula(Request $request, SerializerInterface $serializer)
    {
        $formula = $serializer->deserialize(
            $request->getContent(),
            Formula::class,
            'json'
        );
        return $formula;
    }
}