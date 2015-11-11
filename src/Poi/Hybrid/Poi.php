<?php

namespace Oneup\Contao\Poi\Hybrid;

use Contao\Hybrid;
use Contao\FilesModel;
use Contao\File;
use Contao\Image;
use Contao\ContentModel;
use Contao\BackendTemplate;
use Oneup\Contao\Poi\Model\PointOfInterestModel;
use Oneup\Contao\Poi\Model\PointsOfInterestModel;

class Poi extends Hybrid
{
    protected $strTemplate = "poi_default";

    public function generate()
    {
        if (TL_MODE === 'BE') {
            $template = new BackendTemplate('be_wildcard');

            $template->wildcard = '### POINTS OF INTEREST ###';
            $template->id = $this->id;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $template->parse();
        }

        return parent::generate();
    }

    protected function compile()
    {
        $parent = $this->getParent();
        $poi = PointsOfInterestModel::findBy('id', $parent->poi_id);
        $pois = PointOfInterestModel::findBy('pid', $poi->id);

        $fileModel = FilesModel::findByUuid($poi->singleSRC);

        $file = new File($fileModel->path);
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

            $elements = array();
            $content = ContentModel::findPublishedByPidAndTable($tempPoi['id'], 'tl_point_of_interest');

            if ($content !== null) {
                $count = 0;
                $last = $content->count() - 1;

                while ($content->next()) {
                    $css = array();

                    /** @var ContentModel $objRow */
                    $row = $content->current();

                    // Add the "first" and "last" classes (see #2583)
                    if ($count == 0 || $count == $last) {
                        if ($count == 0) {
                            $css[] = 'first';
                        }

                        if ($count == $count) {
                            $css[] = 'last';
                        }
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

        $GLOBALS['TL_CSS'][] = 'system/modules/points-of-interest/assets/css/poi.css||static';
    }
}
