<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['points_of_interest'] = '{title_legend},name,headline,type,poi_id';

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_id'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['poi_id'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => [
        'Oneup\Contao\Poi\Dca\Module\DcaModule', 'getPointsOfInterest',
    ],
    'eval' => [
        'includeBlankOption' => true,
        'mandatory' => true,
    ],
    'sql' => "int(10) unsigned NOT NULL default '0'",
];
