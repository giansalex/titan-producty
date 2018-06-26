<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 26/06/2018
 * Time: 12:14
 */

namespace App\Controller\Api;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user")
 */
class UserApiController extends AbstractController
{
    /**
     * @Route("/exists/{name}", methods={"GET"}, name="user_api_exists")
     * @param $name
     * @param UserManagerInterface $userManager
     * @return JsonResponse
     */
    public function exists($name = '', UserManagerInterface $userManager): JsonResponse
    {
        if (empty($name)) {
            $this->createNotFoundException();
        }

        $user = $userManager->findUserByUsername($name);

        return $this->json($user !== null);
    }
}