<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 16/05/2018
 * Time: 17:00
 */

namespace App\Controller\Api;

use App\Entity\Product;
use App\Http\BadRequestResponse;
use App\Repository\ProductRepository;
use App\Services\ModelStateInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/product", options={"expose": true})
 */
class ProductApiController extends AbstractController
{
    /**
     * @Route("/", methods={"POST"}, name="productapi_add")
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
        $product = $serializer->deserialize(
            $request->getContent(),
            Product::class,
            'json'
        );

        if (!$validator->valid($product)) {

            return new BadRequestResponse((string) $validator);
        }

        /**@var $product Product */
        $product->setUser($this->getUser());
        $repository->add($product);

        return new Response();
    }
}