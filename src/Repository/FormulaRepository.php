<?php

namespace App\Repository;

use App\Entity\Formula;
use App\Entity\FormulaDetail;
use App\Entity\Material;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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

    public function edit(Formula $newFormula, Formula $formula)
    {
        $em = $this->getEntityManager();

        foreach ($formula->getDetails() as $detail) {
            $em->remove($detail);
        }

        $formula->getDetails()->clear();
        foreach ($newFormula->getDetails() as $detail) {
            $material = $em->getReference(Material::class, $detail->getMaterialId());
            $detail->setMaterial($material)
                ->setFormula($formula);
            $formula->addDetail($detail);
        }

        $em->flush();
    }

    public function getMaterials(int $id, User $user)
    {
        return $this->createQueryBuilder('f')
            ->select('d.materialId AS material_id, m.name, d.amount, d.unit, m.unit AS m_unit, m.price, d.total')
            ->leftJoin('f.details', 'd')
            ->leftJoin('d.material', 'm')
            ->where('f.id = ?1 AND f.user = ?2')
            ->setParameters([
                1 => $id,
                2 => $user,
            ])
            ->getQuery()
            ->getArrayResult();
    }

    public function getMaterialsWithFactor(int $id, User $user, UnitConvertRepository $converter)
    {
        $items = $this->getMaterials($id, $user);
        $total = count($items);

        for ($i = 0; $i < $total; $i++)
        {
            $item = $items[$i];
            $unit = $item['unit'];
            $matUnit = $item['m_unit'];
            unset($items[$i]['m_unit']);

            if ($matUnit == $unit) {
                continue;
            }

            $items[$i]['factor'] = $converter->getFactor($matUnit, $unit);
        }

        return $items;
    }

    /**
     * Duplicate entity.
     *
     * @param int $id
     * @param User $user
     * @return Formula
     */
    public function duplicate($id, User $user)
    {
        /**@var $formula Formula */
        $formula = $this->findOneBy(['user' => $user, 'id' => $id]);

        $newFormula = clone $formula;
        $newFormula->setName($newFormula->getName().' - copia');

        $details = clone $newFormula->getDetails();
        $newFormula->getDetails()->clear();
        $em = $this->getEntityManager();
        foreach ($details as $detail) {
            $newDetail = clone $detail;
            $newDetail->setFormula($newFormula);

            $newFormula->addDetail($newDetail);
        }

        $em->persist($newFormula);
        $em->flush();

        return $newFormula;
    }

    /**
     * Calculate price from changes on details.
     *
     * @param int $id
     * @param User $user
     */
    public function updatePriceFromDetails(int $id, User $user)
    {
        $details = $this->getMaterials($id, $user);

        $price = array_reduce($details, function ($carry, $item) {
            $total = $item->amount * $item->price;
            $carry += $total;

            return $carry;
        });

        $formula = $this->findOneBy(['id' => $id, 'user' => $user]);
        $formula->setPrice($price);

        $this->getEntityManager()->flush();
    }

    public function updateFormulasByMaterial(Material $material)
    {
        $result = $this->createQueryBuilder('c')
                ->select('c.id')
                ->innerJoin('c.details', 'd')
                ->where('d.material = ?1')
                ->groupBy('c.id')
                ->setParameter(1, $material)
                ->getQuery()
                ->getResult();

        foreach ($result as $item) {
            $id = $item->id;

            $this->updatePriceFromDetails($id, $material->getUser());
        }
    }
}
