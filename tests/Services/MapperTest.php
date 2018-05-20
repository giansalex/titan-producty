<?php
/**
 * Created by PhpStorm.
 * User: Atenea
 * Date: 20/05/2018
 * Time: 10:35
 */

namespace App\Tests\Services;

use App\Dto\FormulaDto;
use App\Entity\Formula;
use App\Services\Mapper;
use PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{
    /**
     * @var Mapper
     */
    private $mapper;

    protected function setUp()
    {
        $this->mapper = new Mapper();
    }

    public function testMap()
    {
        $formula = new Formula();
        $formula->setName('MAT 1')
            ->setAmount(2.44)
            ->setUnit('mm')
            ->setNotes('-----');

        /**@var $dto FormulaDto */
        $dto = $this->mapper->map($formula, FormulaDto::class);

        $this->assertEquals($formula->getName(), $dto->name);
        $this->assertEquals($formula->getAmount(), $dto->amount);
        $this->assertEquals($formula->getUnit(), $dto->unit);
        $this->assertEquals($formula->getNotes(), $dto->notes);
        $this->assertTrue(is_string($dto->name));
        $this->assertTrue(is_float($dto->amount));
    }
}