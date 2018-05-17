<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 17/05/2018
 * Time: 17:03
 */

namespace App\Controller\Api;

use App\Entity\Production;
use App\Http\BadRequestResponse;
use App\Repository\ProductionRepository;
use App\Services\ModelStateInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/production", options={"expose": true})
 */
class ProductionApiController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="production_api_list")
     * @param ProductionRepository $repository
     * @return JsonResponse
     */
    public function list(ProductionRepository $repository): JsonResponse
    {
        $items = $repository->findBy(['user' => $this->getUser()]);

        return $this->json($items);
    }

    /**
     * @Route("/", methods={"POST"}, name="production_api_add")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ModelStateInterface $validator
     * @param ProductionRepository $repository
     * @return BadRequestResponse|Response
     */
    public function add(
        Request $request,
        SerializerInterface $serializer,
        ModelStateInterface $validator,
        ProductionRepository $repository)
    {
        $production = $serializer->deserialize(
            $request->getContent(),
            Production::class,
            'json'
        );

        if (!$validator->valid($production)) {

            return new BadRequestResponse((string) $validator);
        }

        /**@var $production Production */
        $production->setUser($this->getUser());
        $repository->add($production);

        return new Response();
    }
}