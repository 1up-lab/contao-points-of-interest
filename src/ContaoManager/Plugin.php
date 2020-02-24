<?php

declare(strict_types=1);

namespace Oneup\Contao\ContaoPointsOfInterestBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Oneup\Contao\ContaoPointsOfInterestBundle\OneupContaoPointsOfInterestBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(OneupContaoPointsOfInterestBundle::class)->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
