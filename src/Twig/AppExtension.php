<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 28/05/2018
 * Time: 21:51
 */

namespace App\Twig;

use App\Services\StateCodeProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    /**
     * @var StateCodeProvider
     */
    private $provider;

    /**
     * AppExtension constructor.
     * @param StateCodeProvider $provider
     */
    public function __construct(StateCodeProvider $provider)
    {
        $this->provider = $provider;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('state', array($this, 'stateFilter')),
            new TwigFilter('currency', array($this, 'currencyFilter')),
        );
    }

    public function stateFilter($code)
    {
        $codes = $this->provider->getCodes();
        if (!isset($codes[$code])) {
            return '';
        }

        return $codes[$code];
    }

    public function currencyFilter($number, $decimals = 2)
    {
        $formatted = number_format($number, $decimals, '.', ',');

        return '$'.$formatted;
    }
}