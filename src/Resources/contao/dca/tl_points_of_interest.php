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
                'label' => &$GLOBALS['TL_LANG']['tl_points_of_interest']['edit'],
                'href' => 'table=tl_point_of_interest',
                'icon' => 'edit.gif',
                'attributes' => 'class="contextmenu"',
            ],
            'editheader' => [
                'label' => &$GLOBALS['TL_LANG']['tl_points_of_interest']['editheader'],
                'href' => 'act=edit',
                'icon' => 'header.gif',
                'attributes' => 'class="edit-header"',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_points_of_interest']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_points_of_interest']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_points_of_interest']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],

    'palettes' => [
        'default' => '
            {pois_legend},title,singleSRC,includeCss,includeJs;
        ',
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
        'singleSRC' => [
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => [
                'fieldType' => 'radio',
                'files' => true,
                'filesOnly' => true,
                'extensions' => \Config::get('validImageTypes'),
                'mandatory' => true,
            ],
            'sql' => 'binary(16) NULL',
            'save_callback' => [
                [Oneup\Contao\ContaoPointsOfInterestBundle\Dca\Helper::class, 'storeFileMetaInformation'],
            ],
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
