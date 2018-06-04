<?php
/**
 * Created by VS Code.
 * User: Giansalex
 * Date: 03/06/2018
 * Time: 19:22
 */

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class SimpleDto
 */
class SimpleDto
{
    /**
     * @Serializer\Type("int")
     * @var int
     */
    public $id;

    /**
     * @Serializer\Type("float")
     * @var float
     */
    public $value;
}