<?php

namespace App\Repository;

use App\Entity\Formula;
use App\Entity\Material;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $product)
    {
        $em = $this->getEntityManager();
        $product->setFormula($em->getReference(Formula::class, $product->getFormulaId()));

        foreach ($product->getDetails() as $detail) {
            $material = $em->getReference(Material::class, $detail->getMaterialId());
            $detail->setMaterial($material)
                ->setProduct($product);
        }

        $em->persist($product);
        $em->flush();
    }

    public function edit(Product $newProduct, Product $product)
    {
        $em = $this->getEntityManager();

        foreach ($product->getDetails() as $detail) {
            $em->remove($detail);
        }

        $product->getDetails()->clear();
        foreach ($newProduct->getDetails() as $detail) {
            $material = $em->getReference(Material::class, $detail->getMaterialId());
            $detail->setMaterial($material)
                ->setProduct($product);
            $product->addDetail($detail);
        }

        $em->flush();
    }

    public function getMaterials(int $id, User $user)
    {
        return $this->createQueryBuilder('p')
            ->select('d.materialId AS material_id, m.name, d.amount, d.unit, m.price, d.total')
            ->leftJoin('p.details', 'd')
            ->leftJoin('d.material', 'm')
            ->where('p.id = ?0 AND p.user = ?1')
            ->setParameters([$id, $user])
            ->getQuery()
            ->getResult();
    }

    /**
     * Duplicate entity.
     *
     * @param int $id
     * @param User $user
     * @return Product
     */
    public function duplicate($id, User $user)
    {
        /**@var $product Product */
        $product = $this->findOneBy(['user' => $user, 'id' => $id]);

        $newProduct = clone $product;
        $newProduct->setName($newProduct->getName().' - copia');

        $details = clone $newProduct->getDetails();
        $newProduct->getDetails()->clear();
        $em = $this->getEntityManager();
        foreach ($details as $detail) {
            $newDetail = clone $detail;
            $newDetail->setProduct($newProduct);

            $newProduct->addDetail($newDetail);
        }

        $em->persist($newProduct);
        $em->flush();

        return $newProduct;
    }
}
