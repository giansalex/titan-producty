<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 13/05/2018
 * Time: 22:34
 */

namespace App\Services;

/**
 * Interface ModelStateInterface
 */
interface ModelStateInterface
{
    /**
     * Is Valid Object
     *
     * @param mixed $object
     * @return bool
     */
    public function valid($object): bool;
}