<?php

namespace App\Controller\Api;

use App\Repository\MaterialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/material", options={"expose": true})
 */
class MaterialApiController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="material_api_list")
     * @param MaterialRepository $repository
     * @return JsonResponse
     */
    public function list(MaterialRepository $repository): JsonResponse
    {
        $items = $repository->findBy(['user' => $this->getUser()]);

        return $this->json($items);
    }
}
