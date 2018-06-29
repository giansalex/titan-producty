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
     * @Route("/exists/{$email}", methods={"GET"}, name="user_api_exists")
     * @param $email
     * @param UserManagerInterface $userManager
     * @return JsonResponse
     */
    public function exists($email, UserManagerInterface $userManager): JsonResponse
    {
        $user = $userManager->findUserByEmail($email);

        return $this->json($user !== null);
    }
}