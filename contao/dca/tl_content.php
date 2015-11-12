<?php

if (TL_MODE === 'BE') {
    // Dynamically add the parent table
    if (Input::get('do') == 'points_of_interest') {
        $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_point_of_interest';

        $this->loadLanguageFile('tl_module');
    }
}

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['Oneup\Contao\Poi\Dca\Content\DcaContent', 'showJsLibraryHint'];

$GLOBALS['TL_DCA']['tl_content']['palettes']['points_of_interest'] = '{title_legend},name,headline,type,poi_id';

$GLOBALS['TL_DCA']['tl_content']['fields']['poi_id'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['poi_id'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => [
        'Oneup\Contao\Poi\Dca\Content\DcaContent', 'getPointsOfInterest',
    ],
    'eval' => [
        'includeBlankOption' => true,
        'mandatory' => true,
    ],
    'sql' => "int(10) unsigned NOT NULL default '0'",
];
