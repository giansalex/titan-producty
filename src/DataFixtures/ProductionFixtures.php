<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 20/05/2018
 * Time: 17:05
 */

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Production;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductionFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProductFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        /**@var $user User */
        $user = $this->getReference(UserFixtures::USER_REFERENCE);

        for ($i = 0; $i < 10; $i++) {
            $production = new Production();
            $production
                ->setProduct($manager->find(Product::class, random_int(1, 10)))
                ->setState('NONE')
                ->setClient('CLIENTE')
                ->setWeight(50)
                ->setPrice(1.24)
                ->setAmount(23)
                ->setUser($user);

            $manager->persist($production);
        }

        $manager->flush();
    }
}