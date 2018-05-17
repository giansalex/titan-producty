<?php

namespace App\Repository;

use App\Entity\Formula;
use App\Entity\Material;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Formula|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formula|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formula[]    findAll()
 * @method Formula[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Formula::class);
    }

    public function add(Formula $formula)
    {
        $em = $this->getEntityManager();

        foreach ($formula->getDetails() as $detail) {
            $material = $em->getReference(Material::class, $detail->getMaterialId());
            $detail->setMaterial($material)
                   ->setFormula($formula);
        }

        $em->persist($formula);
        $em->flush();
    }

    public function getMaterials(int $id, User $user)
    {
        return $this->createQueryBuilder('f')
            ->select('m.name, d.amount, m.unit, d.price, d.total')
            ->leftJoin('f.details', 'd')
            ->leftJoin('d.material', 'm')
            ->where('f.id = ?1 AND f.user = ?2')
            ->setParameters([
                1 => $id,
                2 => $user,
            ])
            ->getQuery()
            ->getResult();
    }
}
