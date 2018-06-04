<?php

namespace App\Repository;

use App\Entity\Material;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Material|null find($id, $lockMode = null, $lockVersion = null)
 * @method Material|null findOneBy(array $criteria, array $orderBy = null)
 * @method Material[]    findAll()
 * @method Material[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Material::class);
    }

    public function updateInventory(array $list, User $user)
    {
        foreach ($list as $item) {
            $material = $this->findOneBy(['id' => $item->id, 'user' => $user]);
            $material->setStock($item->value);
        }

        $this->getEntityManager()->flush();
    }
}
