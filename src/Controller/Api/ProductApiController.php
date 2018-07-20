<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 16/05/2018
 * Time: 17:00
 */

namespace App\Controller\Api;

use App\Dto\ProductDto;
use App\Entity\Product;
use App\Http\BadRequestResponse;
use App\Repository\ProductRepository;
use App\Services\Ensure;
use App\Services\Mapper;
use App\Services\ModelStateInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/product", options={"expose": true})
 */
class ProductApiController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="product_api_list")
     * @param ProductRepository $repository
     * @param Mapper $mapper
     * @return JsonResponse
     */
    public function list(ProductRepository $repository, Mapper $mapper): JsonResponse
    {
        $items = $repository->findBy(['user' => $this->getUser()]);
        $dtos = $mapper->mapArray($items, ProductDto::class);

        return $this->json($dtos);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="product_api_get")
     * @param int $id
     * @param ProductRepository $repository
     * @param Mapper $mapper
     * @return JsonResponse
     */
    public function getItem($id, ProductRepository $repository, Mapper $mapper)
    {
        $product = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $dto = $mapper->map($product, ProductDto::class);

        return $this->json($dto);
    }

    /**
     * @Route("/inventory", methods={"PUT"}, name="product_api_inventory")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ProductRepository $repository
     * @return Response
     */
    public function updateInventory(
        Request $request,
        SerializerInterface $serializer,
        ProductRepository $repository)
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
     * @Route("/", methods={"POST"}, name="product_api_add")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ModelStateInterface $validator
     * @param ProductRepository $repository
     * @return BadRequestResponse|Response
     */
    public function add(
        Request $request,
        SerializerInterface $serializer,
        ModelStateInterface $validator,
        ProductRepository $repository)
    {
        $product = $this->getProduct($request, $serializer);

        if (!$validator->valid($product)) {

            return new BadRequestResponse((string) $validator);
        }

        /**@var $product Product */
        $product->setUser($this->getUser());
        $repository->add($product);

        return new Response();
    }

    /**
     * @Route("/{id}", methods={"PUT"}, name="product_api_edit")
     * @param int $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ModelStateInterface $validator
     * @param ProductRepository $repository
     * @param Mapper $mapper
     * @param Ensure $ensure
     * @return BadRequestResponse|Response
     */
    public function edit(
        $id,
        Request $request,
        SerializerInterface $serializer,
        ModelStateInterface $validator,
        ProductRepository $repository,
        Mapper $mapper,
        Ensure $ensure)
    {
        $product = $repository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
        $ensure->ifNotEmpty($product);

        /**@var $newProduct Product */
        $newProduct = $this->getProduct($request, $serializer);
        $mapper->mapToObject($newProduct, $product);

        if (!$validator->valid($product)) {

            return new BadRequestResponse((string) $validator);
        }
        $repository->edit($newProduct, $product);

        return new Response();
    }

    /**
     * @Route("/{id}/material", methods={"GET"}, name="product_api_material")
     * @param int $id
     * @param ProductRepository $repository
     * @return JsonResponse
     */
    public function materials(int $id, ProductRepository $repository)
    {
        $items = $repository->getMaterials($id, $this->getUser());

        return $this->json($items);
    }

    /**
     * @Route("/{id}", methods={"DELETE"}, name="product_api_remove")
     * @param int $id
     * @param ProductRepository $repository
     * @return Response
     */
    public function remove($id, ProductRepository $repository): Response
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

    /**
     * @Route("/{id}/duplicate", methods={"POST"}, name="product_api_duplicate")
     * @param int $id
     * @param ProductRepository $repository
     * @param Mapper $mapper
     * @return JsonResponse
     */
    public function duplicate(int $id, ProductRepository $repository, Mapper $mapper)
    {
        $newProduct = $repository->duplicate($id, $this->getUser());

        return $this->json($mapper->map($newProduct, ProductDto::class));
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return object
     */
    private function getProduct(Request $request, SerializerInterface $serializer)
    {
        $product = $serializer->deserialize(
            $request->getContent(),
            Product::class,
            'json'
        );
        return $product;
    }
}