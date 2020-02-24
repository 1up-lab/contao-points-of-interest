<?php

declare(strict_types=1);

namespace Oneup\Contao\ContaoPointsOfInterestBundle\PointsOfInterest;

use Contao\ContentModel;
use Contao\FilesModel;
use Contao\Frontend;
use Contao\Image;
use Contao\Model;
use Contao\Template;
use Oneup\Contao\ContaoPointsOfInterestBundle\Model\PointOfInterestModel;
use Oneup\Contao\ContaoPointsOfInterestBundle\Model\PointsOfInterestModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseRenderer
{
    public function getResponse(Template $template, Model $model, Request $request): ?Response
    {
        $poi = PointsOfInterestModel::findOneBy('id', $model->poi_id);

        if (!$poi instanceof PointsOfInterestModel) {
            return $template->getResponse();
        }

        $pois = PointOfInterestModel::findBy('pid', $poi->id);

        $fileModel = FilesModel::findByUuid($poi->singleSRC);

        $file = new \File($fileModel->path);
        $img = new Image($file);

        $imgSize = $file->imageSize;
        // TODO: implement image size

        $poi->image = $img;
        $template->pointsOfInterest = $poi->row();
        $pointsOfInterest = [];

        while (null !== $pois && $pois->next()) {
            $tempPoi = $pois->row();
            $tempPoi['position'] = json_decode($tempPoi['position'], true);

            $tempPoi['position']['pX'] = round(100 / $imgSize[0] * $tempPoi['position']['x'], 3);
            $tempPoi['position']['pY'] = round(100 / $imgSize[1] * $tempPoi['position']['y'], 3);

            $elements = [];

            if (true === (bool) $poi->addLink) {
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
                        $elements[] = Frontend::getContentElement($row, $model->strColumn);
                        ++$count;
                    }
                }
            }

            $tempPoi['content'] = $elements;
            $pointsOfInterest[] = $tempPoi;
        }

        // Sort top -> down (avoids z-index problems)
        usort($pointsOfInterest, static function ($a, $b) {
            return $a['position']['pY'] <=> $b['position']['pY'];
        });

        // Sort left -> right (avoids z-index problems)
        usort($pointsOfInterest, static function ($a, $b) {
            return $a['position']['pX'] <=> $b['position']['pX'];
        });

        $template->pointOfInterest = $pointsOfInterest;

        if ((int) $poi->includeCss) {
            $GLOBALS['TL_CSS'][] = 'system/modules/points-of-interest/assets/css/poi.css||static';
        }

        if ((int) $poi->includeJs) {
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/points-of-interest/assets/js/poi.js';
        }

        return $template->getResponse();
    }
}
