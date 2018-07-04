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

//    /**
//     * @return UnitConvert[] Returns an array of UnitConvert objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UnitConvert
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
