<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/05/2018
 * Time: 21:54
 */

namespace App\Services;


class StateCodeProvider
{
    public function getCodes()
    {
        return [
            '01' => 'En Proceso',
            '02' => 'Terminado',
        ];
    }

    public function getValue(string $code)
    {
        $codes = $this->getCodes();

        if (!isset($codes[$code])) {
            return '';
        }

        return $codes[$code];
    }
}