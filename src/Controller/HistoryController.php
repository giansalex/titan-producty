<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 17/06/2018
 * Time: 12:20
 */

namespace App\Controller;

use App\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/history")
 */
class HistoryController extends AbstractController
{
    /**
     * @Route("/{type}", name="history_index", methods="GET|POST")
     * @param int $type
     * @param HistoryRepository$repository
     * @return Response
     */
    public function index($type, HistoryRepository $repository)
    {
        $items = $repository->listMaterialByType($type, $this->getUser());

        return $this->render('history/index.html.twig', ['histories' => $items]);
    }
}