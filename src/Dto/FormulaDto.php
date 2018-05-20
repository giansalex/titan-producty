<?php
/**
 * Created by PhpStorm.
 * User: Atenea
 * Date: 20/05/2018
 * Time: 9:50
 */

namespace App\Dto;

/**
 * Class FormulaDto
 */
class FormulaDto
{
    /**
     * @var $id int
     */
    public $id;

    /**
     * @var $name string
     */
    public $name;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var $unit string
     */
    public $unit;

    /**
     * @var $notes string
     */
    public $notes;
}