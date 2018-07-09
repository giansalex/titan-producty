<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 9/07/2018
 * Time: 13:19
 */

namespace App\EventSubscriber;

use App\Event\MaterialEditEvent;
use App\Repository\FormulaRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FormulaSubscriber implements EventSubscriberInterface
{
    /**
     * @var FormulaRepository
     */
    private $repository;

    /**
     * FormulaSubscriber constructor.
     * @param FormulaRepository $repository
     */
    public function __construct(FormulaRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @inheritdoc */
    public static function getSubscribedEvents()
    {
        return [
            MaterialEditEvent::NAME => 'onMaterialEdit'
        ];
    }

    public function onMaterialEdit(MaterialEditEvent $event)
    {
        $material = $event->getMaterial();

        $this->repository->updateFormulasByMaterial($material);
    }
}