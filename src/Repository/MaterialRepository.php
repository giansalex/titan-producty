<?php

namespace App\Repository;

use App\Entity\History;
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
        $em = $this->getEntityManager();
        foreach ($list as $item) {
            $material = $this->findOneBy(['id' => $item->id, 'user' => $user]);
            $diff = $item->value - $material->getStock();
            $material->setStock($item->value);

            $history = $this->createHistory($material, $user, $diff);

            $em->persist($history);
        }

        $em->flush();
    }

    public function createHistory(Material $material, User $user, $difference)
    {
        $history = new History();
        $history
            ->setUser($user)
            ->setType(1)
            ->setItemId($material->getId())
            ->setAmount($difference)
            ->setTotal($material->getStock())
            ->setAction('Ajuste Inventario')
            ->setDate(new \DateTime())
            ->setUserAction($user->getId());

        return $history;
    }
}
