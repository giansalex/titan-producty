<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 19/05/2018
 * Time: 14:38
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends Fixture implements ContainerAwareInterface
{
    public const USER_REFERENCE = 'user';

    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();

        $user->setUsername('giansalex');
        $user->setEmail('giansalex@gmail.com');
        $user->setPlainPassword('123456');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_USER']);

        $userManager->updateUser($user, true);
        $this->addReference(self::USER_REFERENCE, $user);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}