<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_points_of_interest'] = [
    'config' => [
        'dataContainer' => 'Table',
        'ctable' => ['tl_point_of_interest'],
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],

    'list' => [
        'sorting' => [
            'mode' => 1,
            'fields' => ['title'],
            'flag' => 1,
            'panelLayout' => 'filter;search,limit',
        ],
        'label' => [
            'fields' => ['title'],
            'format' => '%s',
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'href' => 'table=tl_point_of_interest',
                'icon' => 'edit.gif',
                'attributes' => 'class="contextmenu"',
            ],
            'editheader' => [
                'href' => 'act=edit',
                'icon' => 'header.gif',
                'attributes' => 'class="edit-header"',
            ],
            'copy' => [
                'href' => 'act=copy',
                'icon' => 'copy.gif',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ],
            'show' => [
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],

    'palettes' => [
        '__selector__' => ['addIcon'],
        'default' => '
            {pois_legend},title,singleSRC,size,includeCss,includeJs;
            {icon_legend},addIcon;
        ',
    ],

    'subpalettes' => [
        'addIcon' => 'icon',
    ],

    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => [
                'mandatory' => true,
                'maxlength' => 255,
            ],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'addIcon' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'icon' => [
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => [
                'fieldType' => 'radio',
                'files' => true,
                'filesOnly' => true,
                'extensions' => Contao\Config::get('validImageTypes'),
                'mandatory' => true,
            ],
            'sql' => 'binary(16) NULL',
        ],
        'singleSRC' => [
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => [
                'fieldType' => 'radio',
                'files' => true,
                'filesOnly' => true,
                'extensions' => Contao\Config::get('validImageTypes'),
                'mandatory' => true,
            ],
            'sql' => 'binary(16) NULL',
            'save_callback' => [
                ['oneup_contao_points_of_interest.dca_helper', 'storeFileMetaInformation'],
            ],
        ],
        'size' => [
            'exclude' => true,
            'inputType' => 'imageSize',
            'reference' => &$GLOBALS['TL_LANG']['MSC'],
            'eval' => [
                'rgxp' => 'natural',
                'includeBlankOption' => true,
                'nospace' => true,
                'helpwizard' => true,
                'tl_class' => 'w50',
            ],
            'options_callback' => ['oneup_contao_points_of_interest.image_size_provider', 'getImageSizes'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'includeCss' => [
            'exclude' => true,
            'filter' => true,
            'default' => '1',
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => [
                'doNotCopy' => true,
                'tl_class' => 'w50',
            ],
            'sql' => "char(1) NOT NULL default '1'",
        ],
        'includeJs' => [
            'exclude' => true,
            'filter' => true,
            'default' => '1',
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => [
                'doNotCopy' => true,
                'tl_class' => 'w50',
            ],
            'sql' => "char(1) NOT NULL default '1'",
        ],
    ],
];
