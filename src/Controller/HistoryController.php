<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 17/06/2018
 * Time: 12:20
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/history")
 */
class HistoryController extends AbstractController
{
    /**
     * @Route("/{type}", name="history_index", methods="GET")
     * @param int $type
     * @return Response
     */
    public function index($type)
    {
        return $this->render('history/index.html.twig', ['type' => $type]);
    }
}