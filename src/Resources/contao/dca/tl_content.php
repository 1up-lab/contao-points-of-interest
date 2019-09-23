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

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = [Oneup\Contao\ContaoPointsOfInterestBundle\Dca\Helper::class, 'showJsLibraryHint'];

$GLOBALS['TL_DCA']['tl_content']['palettes']['points_of_interest'] = '
    {title_legend},name,headline,type,poi_id;
';

$GLOBALS['TL_DCA']['tl_content']['fields']['poi_id'] = [
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
