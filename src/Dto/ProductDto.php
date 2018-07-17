<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 20/05/2018
 * Time: 16:28
 */

namespace App\Dto;

/**
 * Class ProductDto
 */
class ProductDto
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $code;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var float
     */
    public $stock;

    /**
     * @var float
     */
    public $price;

    /**
     * @var string
     */
    public $unit;

    /**
     * @var string
     */
    public $notes;

    /**
     * @var float
     */
    public $baseAmount;

    /**
     * @var int
     */
    public $formulaId;
}