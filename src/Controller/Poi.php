<?php

declare(strict_types=1);

namespace Oneup\Contao\Poi\Hybrid;

use Contao\BackendTemplate;
use Contao\ContentModel;
use Contao\FilesModel;
use Contao\Hybrid;
use Contao\Image;
use Oneup\Contao\ContaoPointsOfInterestBundle\Model\PointOfInterestModel;
use Oneup\Contao\ContaoPointsOfInterestBundle\Model\PointsOfInterestModel;

class Poi extends Hybrid
{
    protected $strKey = 'poi';

    public function generate()
    {
        if (TL_MODE === 'BE') {
            $template = new BackendTemplate('be_wildcard');

            $template->wildcard = '### POINTS OF INTEREST ###';
            $template->id = $this->id;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $template->parse();
        }

        return parent::generate();
    }

    protected function compile(): void
    {
        $parent = $this->getParent();
        $poi = PointsOfInterestModel::findOneBy('id', $parent->poi_id);
        $pois = PointOfInterestModel::findBy('pid', $poi->id);

        $fileModel = FilesModel::findByUuid($poi->singleSRC);

        $file = new \File($fileModel->path);
        $img = new Image($file);

        $imgSize = $file->imageSize;
        // TODO: implement image size

        $poi->image = $img;

        $this->Template->pointsOfInterest = $poi->row();

        $arrPois = [];

        while (null !== $pois && $pois->next()) {
            $tempPoi = $pois->row();
            $tempPoi['position'] = json_decode($tempPoi['position'], true);

            $tempPoi['position']['pX'] = round(100 / $imgSize[0] * $tempPoi['position']['x'], 3);
            $tempPoi['position']['pY'] = round(100 / $imgSize[1] * $tempPoi['position']['y'], 3);

            $elements = [];
            $content = ContentModel::findPublishedByPidAndTable($tempPoi['id'], 'tl_point_of_interest');

            if (null !== $content) {
                $count = 0;
                $last = $content->count() - 1;

                while ($content->next()) {
                    $css = [];

                    /** @var ContentModel $objRow */
                    $row = $content->current();

                    // Add the "first" and "last" classes (see #2583)
                    if (0 === $count) {
                        $css[] = 'first';
                    }

                    if ($count === $last) {
                        $css[] = 'last';
                    }

                    $row->classes = $css;
                    $elements[] = $this->getContentElement($row, $this->strColumn);
                    ++$count;
                }
            }

            $tempPoi['content'] = $elements;

            $arrPois[] = $tempPoi;
        }

        $this->Template->pointOfInterest = $arrPois;

        if ((int) $poi->includeCss) {
            $GLOBALS['TL_CSS'][] = 'system/modules/points-of-interest/assets/css/poi.css||static';
        }

        if ((int) $poi->includeJs) {
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/points-of-interest/assets/js/poi.js';
        }
    }
}
