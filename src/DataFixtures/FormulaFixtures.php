<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 20/05/2018
 * Time: 16:52
 */

namespace App\DataFixtures;

use App\Entity\Formula;
use App\Entity\FormulaDetail;
use App\Entity\Material;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class FormulaFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [MaterialFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        /**@var $user User */
        $user = $this->getReference(UserFixtures::USER_REFERENCE);

        for ($i = 0; $i < 10; $i++) {
            $formula = new Formula();
            $formula
                ->setName('FORM '.$i)
                ->setUnit('MM')
                ->setAmount(23)
                ->setUser($user);

            $count = random_int(1, 4);

            for ($j = 0; $j < $count; $j++) {
                $detail = new FormulaDetail();
                $detail
                    ->setAmount(4)
                    ->setPrice(2.3)
                    ->setTotal(9.2)
                    ->setMaterial($manager->find(Material::class, random_int(1, 10)))
                    ->setFormula($formula);
            }

            $manager->persist($formula);
        }

        $manager->flush();
    }
}