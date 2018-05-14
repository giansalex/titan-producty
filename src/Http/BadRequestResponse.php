<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 13/05/2018
 * Time: 22:42
 */

namespace App\Http;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class BadRequestResponse
 */
class BadRequestResponse extends Response
{
    /**
     * BadRequestResponse constructor.
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        parent::__construct($content, 400, ['Content-Type' => 'application/json']);
    }
}