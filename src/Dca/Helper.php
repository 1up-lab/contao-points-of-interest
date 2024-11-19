<?php

declare(strict_types=1);

namespace Oneup\Contao\ContaoPointsOfInterestBundle\Dca;

use Contao\Backend;
use Contao\BackendUser;
use Contao\ContentModel;
use Contao\DataContainer;
use Contao\Environment;
use Contao\File;
use Contao\FilesModel;
use Contao\Image;
use Contao\Input;
use Contao\Message;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Versions;
use Oneup\Contao\ContaoPointsOfInterestBundle\Model\PointsOfInterestModel;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class Helper extends Backend
{
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        parent::__construct();
    }

    public function getPointsOfInterest(): array
    {
        $pois = [];

        $collection = PointsOfInterestModel::findAll([
            'order' => 'title ASC',
        ]);

        if (null === $collection) {
            return $pois;
        }

        while ($collection->next()) {
            $pois[$collection->id] = $collection->title;
        }

        return $pois;
    }

    public function storeFileMetaInformation($varValue, DataContainer $dc)
    {
        if ($dc->activeRecord->singleSRC === $varValue) {
            return $varValue;
        }

        $objFile = FilesModel::findByUuid($varValue);

        if (null !== $objFile) {
            $arrMeta = StringUtil::deserialize($objFile->meta);

            if (!empty($arrMeta)) {
                $objPage = $this->Database->prepare('SELECT * FROM tl_page WHERE id=(SELECT pid FROM ' . ($dc->activeRecord->ptable ?: 'tl_article') . ' WHERE id=?)')
                    ->execute($dc->activeRecord->pid);

                if ($objPage->numRows) {
                    $objModel = new PageModel();
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

    public function showJsLibraryHint($dc): void
    {
        if ($_POST || 'edit' !== Input::get('act')) {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if ($token instanceof TokenInterface) {
            $user = $token->getUser();

            if ($user instanceof BackendUser) {
                $this->User = $user;
            }
        }

        // Return if the user cannot access the layout module (see #6190)
        if (!$this->User->hasAccess('themes', 'modules') || !$this->User->hasAccess('layout', 'themes')) {
            return;
        }

        $objCte = ContentModel::findByPk($dc->id);

        if (null === $objCte) {
            return;
        }

        $i = $objCte->type;

        if ('points_of_interest' === $i) {
            Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_content']['includeTemplate'], 'j_colorbox'));
        }
    }

    public function listPois($arrRow): string
    {
        return '<div class="tl_content_left">' . $arrRow['title'] . '</div>';
    }

    public function showPositionPicker($context): string
    {
        $record = $context->activeRecord;

        $pointOfInterests = PointsOfInterestModel::findByPk($record->pid);

        if (null === $pointOfInterests) {
            // TODO: Show error / exception
            return 'Error loading image';
        }

        $fileModel = FilesModel::findByUuid($pointOfInterests->singleSRC);
        $file = new File($fileModel->path);
        $imgSize = $file->imageSize;

        $imgHtml = '<div class="poi">
            <img id="poi-position-image" src="' . $file->path . '" width="100%" data-original-width="' . $imgSize[0] . '" data-original-height="' . $imgSize[1] . '" />
            <span class="marker not-set"></span>
        </div>';

        $script = '
        <script>
            window.addEvent("domready", function() {
                Backend.poiEditPositionWizard($("poi-position-image"));
            });
        </script>
        ';

        return $imgHtml . $script;
    }

    public function editPoiIcon($row, $href, $label, $title, $icon, $attributes): string
    {
        if (true === (bool) $row['addLink']) {
            return '';
        }

        $href .= '&amp;id=' . $row['id'];

        return '<a href="' . self::addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
    }

    public function togglePoiIcon($row, $href, $label, $title, $icon, $attributes): string
    {
        $tid = Input::get('tid');

        if (null !== $tid && '' !== $tid) {
            $this->toggleVisibility(Input::get('tid'), (1 === (int) Input::get('state')));

            if (Environment::get('isAjaxRequest')) {
                exit;
            }

            self::redirect(self::getReferer());
        }

        $href .= '&amp;id=' . Input::get('id') . '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.gif';
        }

        return '<a href="' . self::addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }

    public function toggleVisibility($intId, $blnVisible): void
    {
        $objVersions = new Versions('tl_point_of_interest', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_point_of_interest']['fields']['published']['save_callback'] ?? null)) {
            foreach ($GLOBALS['TL_DCA']['tl_point_of_interest']['fields']['published']['save_callback'] as $callback) {
                $this->import($callback[0]);
                $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
            }
        }

        $this->Database
            ->prepare('UPDATE tl_point_of_interest SET tstamp=' . time() . ", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
            ->execute($intId);

        $objVersions->create();
    }
}
