<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 03/07/2018
 * Time: 22:45
 */

namespace App\DataFixtures;

use App\Entity\UnitConvert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UnitConverterFixtures extends Fixture
{
    private $data = [
        [
            'from'=> 'kg' ,'to' => 'g', 'factor' => 1000
        ],
        [
            'from'=> 'kg' ,'to' => 'lb', 'factor' => 2.20462
        ],
        [
            'from'=> 'kg' ,'to' => 'ton', 'factor' => 0.001
        ],
        [
            'from'=> 'g' ,'to' => 'lb', 'factor' => 0.00220462
        ],
        [
            'from'=> 'g' ,'to' => 'ton', 'factor' => 0.000001
        ],
        [
            'from'=> 'lb' ,'to' => 'ton', 'factor' => 0.0005
        ],
        [
            'from'=> 'l' ,'to' => 'ml', 'factor' => 1000
        ],
        [
            'from'=> 'l' ,'to' => 'm3', 'factor' => 0.001
        ],
        [
            'from'=> 'l' ,'to' => 'gal', 'factor' => 0.264172
        ],
        [
            'from'=> 'ml' ,'to' => 'm3', 'factor' => 0.000001
        ],
        [
            'from'=> 'ml' ,'to' => 'gal', 'factor' => 0.000264172
        ],
        [
            'from'=> 'gal' ,'to' => 'm3', 'factor' => 0.00378541
        ],
        [
            'from'=> 'm' ,'to' => 'cm', 'factor' => 100
        ],
        [
            'from'=> 'm2' ,'to' => 'dm2', 'factor' => 100
        ],
        [
            'from'=> 'm2' ,'to' => 'cm2', 'factor' => 10000
        ],
        [
            'from'=> 'dm2' ,'to' => 'cm2', 'factor' => 100
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $item) {
            $convert = new UnitConvert();
            $convert->setSource($item['from'])
                ->setTarget($item['to'])
                ->setFactor($item['factor']);

            $manager->persist($convert);
        }

        $manager->flush();
    }
}