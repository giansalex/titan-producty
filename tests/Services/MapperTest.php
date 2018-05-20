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
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{
    /**
     * @var Mapper
     */
    private $mapper;
    /**
     * @var Generator
     */
    private $faker;

    protected function setUp()
    {
        $this->mapper = new Mapper();
        $this->faker = Factory::create('es_ES');
    }

    public function testMap(): void
    {
        $gen = $this->faker;

        $formula = new Formula();
        $formula
            ->setName($gen->text)
            ->setAmount($gen->randomFloat())
            ->setUnit($gen->text)
            ->setNotes($gen->text);

        /**@var $dto FormulaDto */
        $dto = $this->mapper->map($formula, FormulaDto::class);

        $this->assertEquals($formula->getName(), $dto->name);
        $this->assertEquals($formula->getAmount(), $dto->amount);
        $this->assertEquals($formula->getUnit(), $dto->unit);
        $this->assertEquals($formula->getNotes(), $dto->notes);
        $this->assertTrue(is_string($dto->name));
        $this->assertTrue(is_float($dto->amount));
    }

    public function testMapArray(): void
    {
        $gen = $this->faker;
        $formula = new Formula();
        $formula
            ->setName($gen->text)
            ->setAmount($gen->randomFloat())
            ->setUnit($gen->text)
            ->setNotes($gen->text);

        $dtos = $this->mapper->mapArray([$formula], FormulaDto::class);

        $this->assertEquals(1, count($dtos));
        $dto = $dtos[0];
        $this->assertEquals(FormulaDto::class, get_class($dto));
        $this->assertEquals($formula->getName(), $dto->name);
        $this->assertEquals($formula->getAmount(), $dto->amount);
        $this->assertEquals($formula->getUnit(), $dto->unit);
        $this->assertEquals($formula->getNotes(), $dto->notes);
    }
}