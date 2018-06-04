<?php

namespace App\Repository;

use App\Entity\FormulaDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FormulaDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormulaDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormulaDetail[]    findAll()
 * @method FormulaDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulaDetailRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FormulaDetail::class);
    }
}
