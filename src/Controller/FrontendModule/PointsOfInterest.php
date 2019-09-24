<?php

declare(strict_types=1);

namespace Oneup\Contao\ContaoPointsOfInterestBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\ModuleModel;
use Contao\Template;
use Oneup\Contao\ContaoPointsOfInterestBundle\PointsOfInterest\ResponseRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PointsOfInterest extends AbstractFrontendModuleController
{
    /**
     * @var ResponseRenderer
     */
    private $pointsOfInterestResponseRenderer;

    public function __construct(ResponseRenderer $pointsOfInterestResponseRenderer)
    {
        $this->pointsOfInterestResponseRenderer = $pointsOfInterestResponseRenderer;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        return $this->pointsOfInterestResponseRenderer->getResponse($template, $model, $request);
    }
}
