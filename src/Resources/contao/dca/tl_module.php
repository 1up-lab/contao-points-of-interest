<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = [Oneup\Contao\ContaoPointsOfInterestBundle\Dca\Helper::class, 'showJsLibraryHint'];

$GLOBALS['TL_DCA']['tl_module']['palettes']['points_of_interest'] = '
    {title_legend},name,headline,type,poi_id;
';

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_id'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => [
        Oneup\Contao\ContaoPointsOfInterestBundle\Dca\Helper::class, 'getPointsOfInterest',
    ],
    'eval' => [
        'includeBlankOption' => true,
        'mandatory' => true,
    ],
    'sql' => "int(10) unsigned NOT NULL default '0'",
];
