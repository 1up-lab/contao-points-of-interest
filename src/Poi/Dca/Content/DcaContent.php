<?php

namespace Oneup\Contao\Poi\Dca\Content;

use Contao\Input;
use Contao\ContentModel;
use Contao\Message;
use Oneup\Contao\Poi\Dca\Hybrid\DcaHybrid;

class DcaContent extends DcaHybrid
{
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
                Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_content']['includeTemplates'], 'moo_mediabox', 'j_colorbox'));
                break;
        }
    }
}
