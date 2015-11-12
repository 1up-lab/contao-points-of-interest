<?php

namespace Oneup\Contao\Poi\Dca\Hybrid;

use Contao\FilesModel;
use Contao\Input;
use Contao\Backend;
use Contao\ContentModel;
use Contao\Message;
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
            'order' => 'title ASC',
        ]);

        while ($collection->next()) {
            $pois[$collection->id] = $collection->title;
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

    public function showJsLibraryHint($dc)
    {
        if ($_POST || Input::get('act') != 'edit') {
            return;
        }

        // Return if the user cannot access the layout module (see #6190)
        if (!$this->User->hasAccess('themes', 'modules') || !$this->User->hasAccess('layout', 'themes')) {
            return;
        }

        $objCte = ContentModel::findByPk($dc->id);

        if ($objCte === null) {
            return;
        }

        switch ($objCte->type) {
            case 'points_of_interest':
                Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_content']['includeTemplate'], 'j_colorbox'));
                break;
        }
    }
}
