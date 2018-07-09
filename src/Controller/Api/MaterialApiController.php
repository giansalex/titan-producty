<?php

namespace App\Controller\Api;

use App\Entity\Material;
use App\Event\MaterialEditEvent;
use App\Http\BadRequestResponse;
use App\Repository\MaterialRepository;
use App\Services\Ensure;
use App\Services\Mapper;
use App\Services\ModelStateInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @Route("/multiple", methods={"POST"}, name="material_api_multiple")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ModelStateInterface $validator
     * @return BadRequestResponse|Response
     */
    public function multiple(
        Request $request,
        SerializerInterface $serializer,
        ModelStateInterface $validator)
    {
        $materials = $serializer->deserialize(
            $request->getContent(),
            'ArrayCollection<'.Material::class.'>',
            'json'
        );

        if (!$validator->valid($materials)) {

            return new BadRequestResponse((string) $validator);
        }

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        /**@var $material Material */
        foreach ($materials as $material) {
            $material->setUser($user);
            $em->persist($material);
        }
        $em->flush();

        return new Response();
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
        $material = $this->getMaterial($request, $serializer);

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
     * @Route("/{id}", methods={"GET"}, name="material_api_get")
     * @param int $id
     * @param MaterialRepository $repository
     * @return JsonResponse
     */
    public function getItem($id, MaterialRepository $repository)
    {
        $material = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);

        return $this->json($material);
    }

    /**
     * @Route("/inventory", methods={"PUT"}, name="material_api_inventory")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param MaterialRepository $repository
     * @return Response
     */
    public function updateInventory(
        Request $request,
        SerializerInterface $serializer,
        MaterialRepository $repository)
    {
        $list = $serializer->deserialize(
            $request->getContent(),
            'ArrayCollection<App\Dto\SimpleDto>',
            'json'
        );

        $repository->updateInventory($list, $this->getUser());

        return new Response();
    }

    /**
     * @Route("/{id}", methods={"PUT"}, name="material_api_edit")
     * @param int $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ModelStateInterface $validator
     * @param MaterialRepository $repository
     * @param EventDispatcherInterface $dispatcher
     * @param Mapper $mapper
     * @param Ensure $ensure
     * @return BadRequestResponse|Response
     */
    public function edit(
        $id,
        Request $request,
        SerializerInterface $serializer,
        ModelStateInterface $validator,
        MaterialRepository $repository,
        EventDispatcherInterface $dispatcher,
        Mapper $mapper,
        Ensure $ensure)
    {
        $material = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $old = $material->getStock();
        $ensure->ifNotEmpty($material);

        $newMaterial = $this->getMaterial($request, $serializer);
        $mapper->mapToObject($newMaterial, $material);

        if (!$validator->valid($material)) {

            return new BadRequestResponse((string) $validator);
        }

        $em = $this->getDoctrine()->getManager();

        $diff = $material->getStock() - $old;
        if ($diff !== 0) {
            $history = $repository->createHistory($material, $this->getUser(), $diff);
            $em->persist($history);
        }

        $em->flush();
        $dispatcher->dispatch(MaterialEditEvent::NAME, new MaterialEditEvent($material));

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

    /**
     * @Route("/{id}/duplicate", methods={"POST"}, name="material_api_duplicate")
     * @param int $id
     * @param MaterialRepository $repository
     * @return JsonResponse
     */
    public function duplicate(int $id, MaterialRepository $repository)
    {
        $newMaterial = $repository->duplicate($id, $this->getUser());

        return $this->json($newMaterial);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return object
     */
    private function getMaterial(Request $request, SerializerInterface $serializer)
    {
        $material = $serializer->deserialize(
            $request->getContent(),
            Material::class,
            'json'
        );
        return $material;
    }
}
