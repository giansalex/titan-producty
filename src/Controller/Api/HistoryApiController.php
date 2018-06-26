<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 25/06/2018
 * Time: 19:22
 */

namespace App\Controller\Api;

use App\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/history", options={"expose": true})
 */
class HistoryApiController extends AbstractController
{
    /**
     * @Route("/{type}", methods={"GET"}, name="history_api_list")
     * @param int $type
     * @param HistoryRepository $repository
     * @return JsonResponse
     */
    public function list($type, HistoryRepository $repository): JsonResponse
    {
        $items = $repository->getQueryMaterialByUser($this->getUser())
                    ->andWhere('h.type = ?1')
                    ->setParameter(1, $type)
                    ->getQuery()
                    ->getResult();

        return $this->json($items);
    }
}