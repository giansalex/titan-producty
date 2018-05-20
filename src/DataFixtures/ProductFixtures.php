<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 20/05/2018
 * Time: 17:01
 */

namespace App\DataFixtures;

use App\Entity\Formula;
use App\Entity\Material;
use App\Entity\Product;
use App\Entity\ProductDetail;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [FormulaFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        /**@var $user User */
        $user = $this->getReference(UserFixtures::USER_REFERENCE);

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product
                ->setFormula($manager->find(Formula::class, random_int(1, 10)))
                ->setName('PROD '.$i)
                ->setCode('AB')
                ->setBaseAmount(1)
                ->setUnit('MM')
                ->setAmount(23)
                ->setUser($user);

            $count = random_int(1, 4);

            for ($j = 0; $j < $count; $j++) {
                $detail = new ProductDetail();
                $detail
                    ->setAmount(4)
                    ->setPrice(2.3)
                    ->setTotal(9.2)
                    ->setMaterial($manager->find(Material::class, random_int(1, 10)))
                    ->setProduct($product);
            }

            $manager->persist($product);
        }

        $manager->flush();
    }
}