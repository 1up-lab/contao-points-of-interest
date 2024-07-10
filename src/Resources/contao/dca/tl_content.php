<?php

declare(strict_types=1);

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

/** @var RequestStack $requestStack */
$requestStack = \Contao\System::getContainer()->get('request_stack');
$request = $requestStack->getCurrentRequest();

/** @var ScopeMatcher $scopeMatcher */
$scopeMatcher = \Contao\System::getContainer()->get('contao.routing.scope_matcher');

if ($request instanceof \Symfony\Component\HttpFoundation\Request && 'points_of_interest' === \Contao\Input::get('do') && $scopeMatcher->isBackendRequest($requestStack->getCurrentRequest())) {
    $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_point_of_interest';
    $this->loadLanguageFile('tl_module');
}

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['oneup_contao_points_of_interest.dca_helper', 'showJsLibraryHint'];

$GLOBALS['TL_DCA']['tl_content']['palettes']['points_of_interest'] = '
    {type_legend},type,headline;{poi_legend},poi_id,poi_useSorting;
';

$GLOBALS['TL_DCA']['tl_content']['fields']['poi_id'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => ['oneup_contao_points_of_interest.dca_helper', 'getPointsOfInterest'],
    'eval' => [
        'includeBlankOption' => true,
        'mandatory' => true,
        'tl_class' => 'w50',
    ],
    'sql' => "int(10) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['poi_useSorting'] = [
    'exclude' => true,
    'filter' => true,
    'default' => '0',
    'flag' => 1,
    'inputType' => 'checkbox',
    'eval' => [
        'doNotCopy' => true,
        'tl_class' => 'w50 m12',
    ],
    'sql' => "char(1) NOT NULL default '0'",
];
