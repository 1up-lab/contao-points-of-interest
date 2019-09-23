<?php

declare(strict_types=1);

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

/** @var RequestStack $requestStack */
$requestStack = \Contao\System::getContainer()->get('request_stack');
$request = $requestStack->getCurrentRequest();

/** @var ScopeMatcher $scopeMatcher */
$scopeMatcher = \Contao\System::getContainer()->get('contao.routing.scope_matcher');

if ($request instanceof \Symfony\Component\HttpFoundation\Request && $scopeMatcher->isBackendRequest($request)) {
    $GLOBALS['TL_CSS'][] = 'bundles/oneupcontaopointsofinterest/css/poi-be.css';
    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/oneupcontaopointsofinterest/js/poi-be.js';
}

$GLOBALS['BE_MOD']['content']['points_of_interest'] = [
    'tables' => [
        'tl_points_of_interest',
        'tl_point_of_interest',
        'tl_content',
    ],
    'icon' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAFo9M/3AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA85JREFUeNpiYACCPbqO60HExj/fvv8HCCAGHW5+UVZGJgaGSy0T/m+38f0PEAAiAN3/ACkKDhW+LUL/0oWQ/7AqPdUC4Pj16/P9+9Xltr0AwfHqpAIIZAjDQT3n+UDcC2Izm/IKMZtw8K37ychgCdR+g6FGTvvgu0vX/r+/cuO/CiePKWO+tJq5I5/YCWagVdzMLF0AAcQM0tcsr5v45d+fa2mSyiU2/KIt2lz8bxwExMT//v//l+UQ0IJ9H1/erpJQ+/nr42cG1z2rGfb7xTkwc3IwbHj7RBDoZIb3z359v8DEysrAwsXJwMTGBqaZmZgYfv//9wHsTHNeYclGeZ1oNU5eQaBDTICYAei5ThZGRgaAAGJkgIJF6ha5UmycTkDmzxWvH86c8+LefpA4ExvQtUvULeqmPb+9/sDHV+xHPr3+tv/jq/0btWyngBQwLlO37Dz/9cNjGz6RyXxqSgwfrt5kYGNjZ6h9eInHgV/Ml6nh0dUeFwHxycCwZuCSlGAA+eTnt28MXYoG93mZWXlYgA55/e/HTwanLUsYWPl4GVQz4xl+f/zEcC61RBRowwMWYRY2HVagnw+FpTJIezgxPFy3FezNv4wMZwRZ2EyYEsQVvTPvnJHiZGJmeLH/KAMogPhZWBmy7p4x3fj26SawF4Gh2avMycME9L8BMCxUnATEldsV9WNAcgABBg8HUKAUSqu7GfIIOR/79PrEvg+vTgCFn0Ol2UVZ2dVDRGTdgDYxz3hxZ86Zz+/ewg0oklZ3MeIRsky4daK5SFojz0tIsunnv3/8///+Zfj3+w8DMwc7AzPQgq9//xwouX8hSoyVXbhdQT8p8sbxSkZQDBz/9PbhmS9vz3UrGtz4+Oc3N8hQULDJB/swqAFDZbdLKNjjIADy69Vvn6ZMeHYjr1ler4kFGH2OwLQZFCQis/3H79/coMjgUZRj+PvzJ4OAjgY4cUh7OjEws7ODxV6fOMugycmbA0z6c+9+/3KMBWjo7z///7MDw+AFIzMzw4drNxneXbwKdgEjEAoZ6zE8XAsJekagN5iA3uFkYf0F1PcZmF6VmYAJZ1qelGru7Bd3E7//+3sYlAxAilm4uRj+g0wHRiqIDU6iwCgSYGVnWP76ob8kG+dfeQ4udXAgmvAICdXKaTf1P70589Pf3/fjxBRbgTk0+O/fv9L///1jYGFl/fXpz+/zuz68aNn5/vmWNAmVSqC2h/UPLy+DRyMIyLJzsUeIygXrcgto3v/x5fG//wyPgcK/gC4REGJhVeVkZmFa/frRBmCqvgb0NlgPANLga/wP3alrAAAAAElFTkSuQmCC',
];

$GLOBALS['TL_MODELS']['tl_points_of_interest'] = Oneup\Contao\ContaoPointsOfInterestBundle\Model\PointsOfInterestModel::class;
$GLOBALS['TL_MODELS']['tl_point_of_interest'] = Oneup\Contao\ContaoPointsOfInterestBundle\Model\PointOfInterestModel::class;

$GLOBALS['TL_CTE']['includes']['points_of_interest'] = 'Oneup\Contao\Poi\Content\Poi';

array_insert($GLOBALS['FE_MOD'], 2, [
    'miscellaneous' => [
        'points_of_interest' => 'Oneup\Contao\Poi\Module\Poi',
    ],
]);
