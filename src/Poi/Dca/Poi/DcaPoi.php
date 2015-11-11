<?php

namespace Oneup\Contao\Poi\Dca\Poi;

use Contao\FilesModel;
use Contao\File;
use Contao\Input;
use Contao\Environment;
use Contao\Versions;
use Contao\Image;
use Contao\Backend;
use Oneup\Contao\Poi\Model\PointsOfInterestModel;

class DcaPoi extends Backend
{
    public function listPois($arrRow)
    {
        return '<div class="tl_content_left">'.$arrRow['title'].'</div>';
    }

    public function showPositionPicker($context)
    {
        $record = $context->activeRecord;

        $pointOfInterests = PointsOfInterestModel::findByPk($record->pid);

        if (null == $pointOfInterests) {
            // TODO: Show error / exception
            return 'Error loading image';
        }

        $fileModel = FilesModel::findByUuid($pointOfInterests->singleSRC);
        $file = new File($fileModel->path);
        $imgSize = $file->imageSize;

        $imgHtml = '<div class="poi">
            <img id="poi-position-image" src="'.$file->path.'" width="100%" data-original-width="'.$imgSize[0].'" data-original-height="'.$imgSize[1].'" />
            <span class="marker not-set"></span>
        </div>';

        $script = '
        <script>
            window.addEvent("domready", function() {
                Backend.poiEditPositionWizard($("poi-position-image"));
            });
        </script>
        ';

        return $imgHtml.$script;
    }

    public function editSlideIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $href .= '&amp;id='.$row['id'];

        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
    }

    public function toggleSlideIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
            if (Environment::get('isAjaxRequest')) {
                exit;
            }
            $this->redirect($this->getReferer());
        }

        $href .= '&amp;id='.Input::get('id').'&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (! $row['published']) {
            $icon = 'invisible.gif';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
    }

    public function toggleVisibility($intId, $blnVisible)
    {
        $objVersions = new Versions('tl_point_of_interest', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_point_of_interest']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_point_of_interest']['fields']['published']['save_callback'] as $callback) {
                $this->import($callback[0]);
                $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
            }
        }

        $this->Database
            ->prepare("UPDATE tl_point_of_interest SET tstamp=".time().", published='".($blnVisible ? 1 : '')."' WHERE id=?")
            ->execute($intId);

        $objVersions->create();
    }
}
