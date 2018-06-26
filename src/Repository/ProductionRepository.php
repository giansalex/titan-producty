<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Production;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Production|null find($id, $lockMode = null, $lockVersion = null)
 * @method Production|null findOneBy(array $criteria, array $orderBy = null)
 * @method Production[]    findAll()
 * @method Production[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Production::class);
    }

    public function add(Production $production)
    {
        $em = $this->getEntityManager();
        $production->setProduct($em->getReference(Product::class, $production->getProductId()));

        $em->persist($production);
        $em->flush();
    }

    public function edit(Production $production)
    {
        $em = $this->getEntityManager();
        $production->setProduct($em->getReference(Product::class, $production->getProductId()));

        $em->flush();
    }

    /**
     * Duplicate entity.
     *
     * @param int $id
     * @param User $user
     * @return Production
     */
    public function duplicate($id, User $user)
    {
        /**@var $production Production */
        $production = $this->findOneBy(['user' => $user, 'id' => $id]);

        $newProduction = clone $production;

        $em = $this->getEntityManager();
        $em->persist($newProduction);
        $em->flush();

        return $newProduction;
    }
}
