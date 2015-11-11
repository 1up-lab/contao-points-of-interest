<?php

namespace Oneup\Contao\Poi\Dca\Hybrid;

use Contao\FilesModel;
use Contao\Input;
use Contao\Backend;
use Oneup\Contao\Poi\Model\PointsOfInterestModel;

class DcaHybrid extends Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function getPointsOfInterest()
    {
        $pois = [];

        $collection = PointsOfInterestModel::findAll([
            'order' => 'name ASC',
        ]);

        while ($collection->next()) {
            $pois[$collection->id] = $collection->name;
        }

        return $pois;
    }

    public function storeFileMetaInformation($varValue, \DataContainer $dc)
    {
        if ($dc->activeRecord->singleSRC == $varValue) {
            return $varValue;
        }

        $objFile = FilesModel::findByUuid($varValue);

        if ($objFile !== null) {
            $arrMeta = deserialize($objFile->meta);

            if (!empty($arrMeta)) {
                $objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=(SELECT pid FROM ".($dc->activeRecord->ptable ?: 'tl_article')." WHERE id=?)")
                    ->execute($dc->activeRecord->pid);

                if ($objPage->numRows) {
                    $objModel = new \PageModel();
                    $objModel->setRow($objPage->row());
                    $objModel->loadDetails();

                    // Convert the language to a locale (see #5678)
                    $strLanguage = str_replace('-', '_', $objModel->rootLanguage);

                    if (isset($arrMeta[$strLanguage])) {
                        Input::setPost('alt', $arrMeta[$strLanguage]['title']);
                        Input::setPost('caption', $arrMeta[$strLanguage]['caption']);
                    }
                }
            }
        }

        return $varValue;
    }
}
