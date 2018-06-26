<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 25/06/2018
 * Time: 22:21
 */

namespace App\Controller\Api;

use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/unit", options={"expose": true})
 */
class UnitApiController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="unit_api_list")
     * @param UnitRepository $repository
     * @return JsonResponse
     */
    public function list(UnitRepository $repository): JsonResponse
    {
        $items = $repository->findAll();

        return $this->json($items);
    }
}