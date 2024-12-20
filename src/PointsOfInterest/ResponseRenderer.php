<?php

declare(strict_types=1);

namespace Oneup\Contao\ContaoPointsOfInterestBundle\PointsOfInterest;

use Contao\ContentModel;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\File;
use Contao\FilesModel;
use Contao\Frontend;
use Contao\Model;
use Contao\StringUtil;
use Contao\Template;
use Oneup\Contao\ContaoPointsOfInterestBundle\Model\PointOfInterestModel;
use Oneup\Contao\ContaoPointsOfInterestBundle\Model\PointsOfInterestModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseRenderer
{
    public function __construct(
        private readonly Studio $studio
    ) {
    }

    public function getResponse(Template $template, Model $model, Request $request): ?Response
    {
        $poi = PointsOfInterestModel::findOneBy('id', $model->poi_id);

        if (!$poi instanceof PointsOfInterestModel) {
            return $template->getResponse();
        }

        $pois = PointOfInterestModel::findPublishedByPid($poi->id);

        $fileModel = FilesModel::findByUuid($poi->singleSRC);

        if (!$fileModel instanceof FilesModel) {
            return $template->getResponse();
        }

        $poiIcon = $poi->addIcon ? $this->getIconPath($poi->icon) : null;
        $poi->size = StringUtil::deserialize($poi->size);
        $file = new File($fileModel->path);

        $imgSize = $file->imageSize;

        $template->pointsOfInterest = $poi->row();
        $pointsOfInterest = [];

        $figure = $this->studio
            ->createFigureBuilder()
            ->fromUuid($poi->singleSRC ?: '')
            ->setSize(StringUtil::deserialize($poi->size))
            ->buildIfResourceExists()
        ;

        $figure?->applyLegacyTemplateData($template);

        while (null !== $pois && $pois->next()) {
            $tempPoi = $pois->row();
            $tempPoi['position'] = json_decode(StringUtil::decodeEntities($tempPoi['position']), true);

            $tempPoi['position']['pX'] = round(100 / $imgSize[0] * $tempPoi['position']['x'], 3);
            $tempPoi['position']['pY'] = round(100 / $imgSize[1] * $tempPoi['position']['y'], 3);

            if ($tempPoi['addIcon'] && $tempPoi['icon']) {
                $tempPoi['icon'] = $this->getIconPath($tempPoi['icon']);
            }

            if ($poiIcon && !$tempPoi['addIcon']) {
                $tempPoi['addIcon'] = true;
                $tempPoi['icon'] = $poiIcon;
            }

            $tempPoi['label'] = $tempPoi['addCustomLabel'] ? $tempPoi['customLabel'] : null;

            $elements = [];

            if (false === (bool) $poi->addLink) {
                $content = ContentModel::findPublishedByPidAndTable($tempPoi['id'], 'tl_point_of_interest');

                if (null !== $content) {
                    $count = 0;
                    $last = $content->count() - 1;

                    while ($content->next()) {
                        $css = [];

                        /** @var ContentModel $objRow */
                        $row = $content->current();

                        if (0 === $count) {
                            $css[] = 'first';
                        }

                        if ($count === $last) {
                            $css[] = 'last';
                        }

                        $row->classes = $css;
                        $elements[] = Frontend::getContentElement($row, $model->strColumn ?? 'main');
                        ++$count;
                    }
                }
            }

            $tempPoi['content'] = $elements;
            $pointsOfInterest[] = $tempPoi;
        }

        if (false === (bool) $model->poi_useSorting) {
            // Sort top -> down (avoids z-index problems)
            usort($pointsOfInterest, static function ($a, $b) {
                return $a['position']['pY'] <=> $b['position']['pY'];
            });

            // Sort left -> right (avoids z-index problems)
            usort($pointsOfInterest, static function ($a, $b) {
                return $a['position']['pX'] <=> $b['position']['pX'];
            });
        }

        $template->pointOfInterest = $pointsOfInterest;

        if ((int) $poi->includeCss) {
            $GLOBALS['TL_CSS'][] = 'bundles/oneupcontaopointsofinterest/css/poi.css||static';
        }

        if ((int) $poi->includeJs) {
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/oneupcontaopointsofinterest/js/poi.js';
        }

        return $template->getResponse();
    }

    public function getIconPath($icon): ?string
    {
        $file = FilesModel::findByUuid($icon);

        if ($file instanceof FilesModel) {
            return $file->path;
        }

        return null;
    }
}
