<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 14/05/2018
 * Time: 12:40
 */

namespace App\Services;

use JMS\Serializer\SerializerInterface;

/**
 * Symfony adapter from Jms
 *
 * Class SerializerAdapter
 */
class SerializerAdapter
{
    /**
     * @var SerializerInterface
     */
    private $jmsSerializer;

    /**
     * SerializerAdapter constructor.
     * @param SerializerInterface $jmsSerializer
     */
    public function __construct(SerializerInterface $jmsSerializer)
    {
        $this->jmsSerializer = $jmsSerializer;
    }

    /**
     * @param $data
     * @param $format
     * @param array $context
     * @return string
     */
    public function serialize($data, $format, array $context = array())
    {
        return $this->jmsSerializer->serialize($data, $format);
    }
}