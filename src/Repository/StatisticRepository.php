<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 29/05/2018
 * Time: 21:04
 */

namespace App\Repository;

use App\Entity\User;

class StatisticRepository
{
    /**
     * @var MaterialRepository
     */
    private $matRepository;
    /**
     * @var FormulaRepository
     */
    private $formulaRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var ProductionRepository
     */
    private $productionRepository;

    /**
     * StatisticRepository constructor.
     * @param MaterialRepository $matRepository
     * @param FormulaRepository $formulaRepository
     * @param ProductRepository $productRepository
     * @param ProductionRepository $productionRepository
     */
    public function __construct(MaterialRepository $matRepository, FormulaRepository $formulaRepository, ProductRepository $productRepository, ProductionRepository $productionRepository)
    {
        $this->matRepository = $matRepository;
        $this->formulaRepository = $formulaRepository;
        $this->productRepository = $productRepository;
        $this->productionRepository = $productionRepository;
    }

    /**
     * Summary count all entities.
     *
     * @param User $user
     * @return array
     */
    public function countAll(User $user) {
        $criteria = ['user' => $user];

        return [
            'materials' => $this->matRepository->count($criteria),
            'formulas'  => $this->formulaRepository->count($criteria),
            'products'  => $this->productRepository->count($criteria),
            'productions'  => $this->productionRepository->count($criteria),
        ];
    }
}