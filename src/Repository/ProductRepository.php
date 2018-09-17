<?php

namespace App\Repository;

use App\Entity\Formula;
use App\Entity\History;
use App\Entity\HistoryType;
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
            ->select('d.materialId AS material_id, m.name, d.amount, d.unit, m.unit AS m_unit, m.price, d.total')
            ->leftJoin('p.details', 'd')
            ->leftJoin('d.material', 'm')
            ->where('p.id = ?0 AND p.user = ?1')
            ->setParameters([$id, $user])
            ->getQuery()
            ->getResult();
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

    public function updateInventory(array $list, User $user)
    {
        $em = $this->getEntityManager();
        foreach ($list as $item) {
            $product = $this->findOneBy(['id' => $item->id, 'user' => $user]);
            $diff = $product->getStock() - $item->value;
            $product->setStock($item->value);

            $history = $this->createHistory($product, $user, $diff);

            $em->persist($history);
        }

        $em->flush();
    }

    public function createHistory(Product $product, User $user, $difference)
    {
        $history = new History();
        $history
            ->setUser($user)
            ->setType(HistoryType::PRODUCT)
            ->setItemId($product->getId())
            ->setAmount($difference)
            ->setTotal($product->getStock())
            ->setAction('Ajuste Inventario')
            ->setDate(new \DateTime())
            ->setUserAction($user->getId());

        return $history;
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
