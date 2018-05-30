<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 10/05/2018
 * Time: 12:34
 */

namespace App\Controller;

use App\Repository\StatisticRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     * @return Response
     */
    public function index(StatisticRepository $repository) : Response
    {
        $params = ['totals' => $repository->countAll($this->getUser())];

        return $this->render('home/index.html.twig', $params);
    }
}