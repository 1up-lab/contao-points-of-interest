<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = ['oneup_contao_points_of_interest.dca_helper', 'showJsLibraryHint'];

$GLOBALS['TL_DCA']['tl_module']['palettes']['points_of_interest'] = '
    {title_legend},name,headline,type,poi_id;
';

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_id'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => ['oneup_contao_points_of_interest.dca_helper', 'getPointsOfInterest'],
    'eval' => [
        'includeBlankOption' => true,
        'mandatory' => true,
    ],
    'sql' => "int(10) unsigned NOT NULL default '0'",
];
