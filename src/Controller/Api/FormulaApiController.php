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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/formula", options={"expose": true})
 */
class FormulaApiController extends AbstractController
{
    /**
     * @Route("/", methods={"POST"}, name="formulaapi_add")
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
}