<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 13/05/2018
 * Time: 21:51
 */

namespace App\Controller\Api;

use App\Entity\Formula;
use App\Entity\FormulaDetail;
use App\Http\BadRequestResponse;
use App\Services\ModelStateInterface;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @Route("/api/formula")
 */
class FormulaApiController  extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="formula_add", options={"expose": true})
     * @param Request $request
     * @param DenormalizerInterface $serializer
     * @param ModelStateInterface $validator
     * @return BadRequestResponse|Response
     */
    public function add(
        Request $request,
        DenormalizerInterface $serializer,
        ModelStateInterface $validator)
    {
        $obj = json_decode($request->getContent(), true);
        $formula = $serializer->denormalize($obj['head'], Formula::class);
        $details = $serializer->denormalize($obj['details'], FormulaDetail::class.'[]');
        /**@var $formula Formula */
        $formula->setUser($this->getUser());

        if (!$validator->valid($formula)) {

            return new BadRequestResponse((string) $validator);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($formula);
        $em->flush();

        foreach ($details as $detail) {
            /**@var $detail FormulaDetail*/
            $detail->setFormula($formula);
            $em->persist($detail);
        }
        $em->flush();

        return new Response();
    }
}