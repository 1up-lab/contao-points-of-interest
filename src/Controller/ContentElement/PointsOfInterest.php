<?php

declare(strict_types=1);

namespace Oneup\Contao\ContaoPointsOfInterestBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\Template;
use Oneup\Contao\ContaoPointsOfInterestBundle\PointsOfInterest\ResponseRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PointsOfInterest extends AbstractContentElementController
{
    /**
     * @var ResponseRenderer
     */
    private $pointsOfInterestResponseRenderer;

    public function __construct(ResponseRenderer $pointsOfInterestResponseRenderer)
    {
        $this->pointsOfInterestResponseRenderer = $pointsOfInterestResponseRenderer;
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        return $this->pointsOfInterestResponseRenderer->getResponse($template, $model, $request);
    }
}
