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
        $prodIds = $this->getProductIds($manager);

        for ($i = 0; $i < 10; $i++) {
            $productId = $prodIds[array_rand($prodIds)];
            $production = new Production();
            $production
                ->setProduct($manager->find(Product::class, $productId))
                ->setState('01')
                ->setClient('CLIENTE')
                ->setWeight(50)
                ->setPrice(1.24)
                ->setAmount(23)
                ->setUser($user);

            $manager->persist($production);
        }

        $manager->flush();
    }

    private function getProductIds(ObjectManager $manager)
    {
        $ids = $manager->getRepository(Product::class)
            ->createQueryBuilder('c')
            ->select('c.id')
            ->getQuery()
            ->getArrayResult();

        return array_map(function ($item) {
            return $item['id'];
        }, $ids);
    }
}