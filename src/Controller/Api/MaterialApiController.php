<?php

namespace App\Controller\Api;

use App\Entity\Material;
use App\Http\BadRequestResponse;
use App\Repository\MaterialRepository;
use App\Services\ModelStateInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @Route("/", methods={"POST"}, name="material_api_add")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ModelStateInterface $validator
     * @return BadRequestResponse|Response
     */
    public function add(
        Request $request,
        SerializerInterface $serializer,
        ModelStateInterface $validator)
    {
        $product = $serializer->deserialize(
            $request->getContent(),
            Material::class,
            'json'
        );

        if (!$validator->valid($product)) {

            return new BadRequestResponse((string) $validator);
        }

        /**@var $product Material */
        $product->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response();
    }

    /**
     * @Route("/{id}", methods={"DELETE"}, name="material_api_remove")
     * @param int $id
     * @param MaterialRepository $repository
     * @return Response
     */
    public function remove($id, MaterialRepository $repository): Response
    {
        $formula = $repository->findBy(['id' => $id, 'user' => $this->getUser()]);

        if (empty($formula)) {
            $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($formula);
        $em->flush();

        return new Response();
    }
}
