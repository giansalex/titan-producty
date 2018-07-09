<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 9/07/2018
 * Time: 13:16
 */

namespace App\Event;

use App\Entity\Material;
use Symfony\Component\EventDispatcher\Event;

class MaterialEditEvent extends Event
{
    public const NAME = 'material.onEdit';

    /**
     * @var Material
     */
    private $material;

    /**
     * MaterialEditEvent constructor.
     * @param Material $material
     */
    public function __construct(Material $material)
    {
        $this->material = $material;
    }

    /**
     * @return Material
     */
    public function getMaterial(): Material
    {
        return $this->material;
    }

    /**
     * @param Material $material
     */
    public function setMaterial(Material $material)
    {
        $this->material = $material;
    }
}