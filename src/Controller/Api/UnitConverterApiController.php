<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 03/07/2018
 * Time: 22:57
 */

namespace App\Controller\Api;

use App\Repository\UnitConvertRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/unit-convert", options={"expose": true})
 */
class UnitConverterApiController extends AbstractController
{
    /**
     * @Route("/from/{source}/to/{target}", methods={"GET"}, name="unitconvert_api_factor")
     * @Cache(expires="tomorrow")
     * @param string $source
     * @param string $target
     * @param UnitConvertRepository $repository
     * @return JsonResponse
     */
    public function list($source , $target, UnitConvertRepository $repository)
    {
        $factor = $repository->getFactor($source, $target);

        if ($factor === null) {
            throw $this->createNotFoundException();
        }

        return $this->json(['factor' => $factor]);
    }
}