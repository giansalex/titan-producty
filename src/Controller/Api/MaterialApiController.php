<?php

namespace App\Controller\Api;

use App\Entity\Material;
use App\Http\BadRequestResponse;
use App\Repository\MaterialRepository;
use App\Services\Ensure;
use App\Services\ModelStateInterface;
use JMS\Serializer\DeserializationContext;
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
        $material = $serializer->deserialize(
            $request->getContent(),
            Material::class,
            'json'
        );

        if (!$validator->valid($material)) {

            return new BadRequestResponse((string) $validator);
        }

        /**@var $material Material */
        $material->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($material);
        $em->flush();

        return new Response();
    }

    /**
     * @Route("/{id}", methods={"PUT"}, name="material_api_edit")
     * @param int $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ModelStateInterface $validator
     * @param MaterialRepository $repository
     * @param Ensure $ensure
     * @return BadRequestResponse|Response
     */
    public function edit(
        $id,
        Request $request,
        SerializerInterface $serializer,
        ModelStateInterface $validator,
        MaterialRepository $repository,
        Ensure $ensure)
    {
        $material = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $ensure->ifNotEmpty($material);

        $context = new DeserializationContext();
        $context->attributes->set('target', $material);

        $serializer->deserialize(
            $request->getContent(),
            Material::class,
            'json',
            $context
        );

        if (!$validator->valid($material)) {

            return new BadRequestResponse((string) $validator);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($material);
        $em->flush();

        return new Response();
    }

    /**
     * @Route("/{id}", methods={"DELETE"}, name="material_api_remove")
     * @param int $id
     * @param MaterialRepository $repository
     * @param Ensure $ensure
     * @return Response
     */
    public function remove($id, MaterialRepository $repository, Ensure $ensure): Response
    {
        $material = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $ensure->ifNotEmpty($material);

        $em = $this->getDoctrine()->getManager();
        $em->remove($material);
        $em->flush();

        return new Response();
    }
}
