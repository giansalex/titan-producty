<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 23/03/2017
 * Time: 18:18.
 */

namespace AppBundle\Util;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Ayuda en validaciones.
 *
 * Class Ensure
 */
class Ensure
{
    /**
     * Verifica que el objeto no sea vacio o null.
     *
     * @param $obj
     *
     * @throws NotFoundHttpException
     */
    public function ifNotEmpty($obj)
    {
        if (empty($obj)) {
            throw new NotFoundHttpException('Resource not found');
        }
    }
}
