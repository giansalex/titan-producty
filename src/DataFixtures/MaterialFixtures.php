<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 19/05/2018
 * Time: 14:45
 */

namespace App\DataFixtures;

use App\Entity\Material;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MaterialFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /**@var $user User */
        $user = $this->getReference(UserFixtures::USER_REFERENCE);

        for ($i = 0; $i < 10; $i++) {
            $material = new Material();
            $material->setName('MAT '.$i)
                ->setUnit('MM')
                ->setCode('C01')
                ->setPrice(2.11)
                ->setAmount(3)
                ->setPackingPrice(4.67)
                ->setStock(20)
                ->setUser($user);

            $manager->persist($material);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}