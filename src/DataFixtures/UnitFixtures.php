<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 19/05/2018
 * Time: 14:45
 */

namespace App\DataFixtures;

use App\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UnitFixtures extends Fixture
{
    private $data = [
        [
            'code'=> 'kg' ,'value' => 'Kilogramo', 'type' => 'Masa'
        ],
        [
            'code'=> 'g' ,'value' => 'Gramo', 'type' => 'Masa'
        ],
        [
            'code'=> 'lb' ,'value' => 'Libra', 'type' => 'Masa'
        ],
        [
            'code'=> 'ton' ,'value' => 'Tonelada', 'type' => 'Masa'
        ],
        [
            'code'=> 'l' ,'value' => 'Litro', 'type' => 'Volumen'
        ],
        [
            'code'=> 'ml' ,'value' => 'Mililitro', 'type' => 'Volumen'
        ],
        [
            'code'=> 'm3' ,'value' => 'Metro Cúbico', 'type' => 'Volumen'
        ],
        [
            'code'=> 'gal' ,'value' => 'Galón', 'type' => 'Volumen'
        ],
        [
            'code'=> 'm' ,'value' => 'Metro', 'type' => 'Longitud'
        ],
        [
            'code'=> 'cm' ,'value' => 'Centímetro', 'type' => 'Longitud'
        ],
        [
            'code'=> 'm2' ,'value' => 'Metro Cuadrado', 'type' => 'Area'
        ],
        [
            'code'=> 'dm2' ,'value' => 'Decímetro Cuadrado', 'type' => 'Area'
        ],
        [
            'code'=> 'cm2' ,'value' => 'Centímetro Cuadrado', 'type' => 'Area'
        ],
        [
            'code'=> 'und' ,'value' => 'Unidades', 'type' => 'Unidades'
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $item) {
            $unit = new Unit();
            $unit->setCode($item['code'])
                ->setValue($item['value'])
                ->setType($item['type']);

            $manager->persist($unit);
        }

        $manager->flush();
    }
}