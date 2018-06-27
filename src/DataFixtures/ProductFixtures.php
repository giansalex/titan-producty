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
        $matIds = $this->getMaterialIds($manager);
        $formulaIds = $this->getFormulaIds($manager);

        for ($i = 0; $i < 10; $i++) {
            $formulaId = $formulaIds[array_rand($formulaIds)];
            $product = new Product();
            $product
                ->setFormula($manager->find(Formula::class, $formulaId))
                ->setName('PROD '.$i)
                ->setCode('AB')
                ->setBaseAmount(1)
                ->setUnit('mm')
                ->setAmount(23)
                ->setUser($user);

            $count = random_int(1, 4);

            for ($j = 0; $j < $count; $j++) {
                $materialId = $matIds[array_rand($matIds)];
                $detail = new ProductDetail();
                $detail
                    ->setAmount(4)
                    ->setPrice(2.3)
                    ->setTotal(9.2)
                    ->setMaterial($manager->find(Material::class, $materialId))
                    ->setProduct($product);

                $product->addDetail($detail);
            }

            $manager->persist($product);
        }

        $manager->flush();
    }

    private function getMaterialIds(ObjectManager $manager)
    {
        $ids = $manager->getRepository(Material::class)
            ->createQueryBuilder('c')
            ->select('c.id')
            ->getQuery()
            ->getArrayResult();

        return array_map(function ($item) {
            return $item['id'];
        }, $ids);
    }

    private function getFormulaIds(ObjectManager $manager)
    {
        $ids = $manager->getRepository(Formula::class)
            ->createQueryBuilder('c')
            ->select('c.id')
            ->getQuery()
            ->getArrayResult();

        return array_map(function ($item) {
            return $item['id'];
        }, $ids);
    }
}