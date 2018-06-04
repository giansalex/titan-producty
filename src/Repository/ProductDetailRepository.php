<?php

namespace App\Repository;

use App\Entity\ProductDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDetail[]    findAll()
 * @method ProductDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDetailRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductDetail::class);
    }
}
