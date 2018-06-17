<?php

namespace App\Repository;

use App\Entity\History;
use App\Entity\Material;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function listMaterialByType($type, User $user)
    {
        return $this->createQueryBuilder('h')
            ->select('h.id, h.date, m.id AS ide, m.name, m.code, h.action, h.amount, h.total, u.username')
            ->innerJoin(Material::class, 'm', Join::WITH, 'h.itemId = m.id')
            ->innerJoin(User::class, 'u', Join::WITH, 'h.userAction = u.id')
            ->where('h.user = ?0 AND h.type = ?1')
            ->setParameters([$user, $type])
            ->getQuery()
            ->getResult();
    }
}
