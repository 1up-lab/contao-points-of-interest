<?php

$GLOBALS['TL_DCA']['tl_point_of_interest'] = [

    'config' => [
        'dataContainer' => 'Table',
        'ptable' => 'tl_points_of_interest',
        'ctable' => ['tl_content'],
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
            ],
        ],
    ],

    'list' => [
        'sorting' => [
            'mode' => 4,
            'fields' => ['sorting'],
            'headerFields' => ['title'],
            'panelLayout' => 'limit',
            'child_record_callback' => ['Oneup\Contao\Poi\Dca\Poi\DcaPoi', 'listPois'],
            'child_record_class' => 'no_padding',
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
                'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['edit'],
                'href' => 'table=tl_content',
                'icon' => 'edit.gif',
                'attributes' => 'class="contextmenu"',
                'button_callback' => ['Oneup\Contao\Poi\Dca\Poi\DcaPoi', 'editSlideIcon'],
            ],
            'editheader' => [
                'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['editheader'],
                'href' => 'act=edit',
                'icon' => 'header.gif',
                'attributes' => 'class="edit-header"',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['copy'],
                'href' => 'act=paste&amp;mode=copy',
                'icon' => 'copy.gif',
            ],
            'cut' => [
                'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['cut'],
                'href' => 'act=paste&amp;mode=cut',
                'icon' => 'cut.gif',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
            ],
            'toggle' => [
                'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['toggle'],
                'icon' => 'visible.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => ['Oneup\Contao\Poi\Dca\Poi\DcaPoi', 'toggleSlideIcon'],
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],

    'palettes' => [
        'default' => '{title_legend},title;{configuration_legend},position;{expert_legend},cssId,cssClass;{publish_legend},published,start,stop',
    ],

    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'pid' => [
            'foreignKey' => 'tl_points_of_interest.id',
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => ['type' => 'belongsTo', 'load' => 'eager'],
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'sorting' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['title'],
            'exclude' => true,
            'search' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'position' => [
            'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['position'],
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'clr',
            ],
            'wizard' => [
                ['Oneup\Contao\Poi\Dca\Poi\DcaPoi', 'showPositionPicker'],
            ],
            'sql' => "varchar(64) NOT NULL default '0'",
        ],
        'cssID' => [
            'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['cssID'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => [
                'multiple' => true,
                'size' => 2,
                'tl_class' => 'w50 clr',
            ],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'published' => [
            'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['published'],
            'exclude' => true,
            'filter' => true,
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'start' => [
            'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['start'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'stop' => [
            'label' => &$GLOBALS['TL_LANG']['tl_point_of_interest']['stop'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
    ],
];
