<?php

namespace App\Repository;

use App\Entity\UnitConvert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UnitConvert|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnitConvert|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnitConvert[]    findAll()
 * @method UnitConvert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitConvertRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UnitConvert::class);
    }

    /**
     * @param string $source
     * @param string $target
     * @return float|null
     */
    public function getFactor($source, $target)
    {
        $converter = $this->findOneBy(['source' => $source, 'target' => $target]);

        if ($converter) {
            return $converter->getFactor();
        }

        $converter = $this->findOneBy(['source' => $target, 'target' => $source]);

        if ($converter) {
            return 1 / $converter->getFactor();
        }

        return null;
    }
}