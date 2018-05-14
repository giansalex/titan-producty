<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 13/05/2018
 * Time: 22:35
 */

namespace App\Services;

use Symfony\Component\Validator\{
    ConstraintViolationInterface,
    ConstraintViolationListInterface
};
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ModelState
 */
class ModelState implements ModelStateInterface
{
    private const MESSAGE = 'Invalid Request';

    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;

    /**
     * ModelState constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Is Valid Object
     *
     * @param mixed $object
     * @return bool
     */
    public function valid($object): bool
    {
        $errors = $this->validator->validate($object);
        $this->errors = $errors;

        return count($errors) == 0;
    }

    public function __toString()
    {
        if (empty($this->errors)) {
            return '{}';
        }

        $result = [];

        foreach ($this->errors as $error) {
            /**@var $error ConstraintViolationInterface*/
            $result[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        return json_encode([
            'message' => self::MESSAGE,
            'errors' => $result
        ]);
    }
}