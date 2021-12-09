<?php

declare(strict_types=1);

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
            'child_record_callback' => ['oneup_contao_points_of_interest.dca_helper', 'listPois'],
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
                'href' => 'table=tl_content',
                'icon' => 'edit.gif',
                'attributes' => 'class="contextmenu"',
                'button_callback' => ['oneup_contao_points_of_interest.dca_helper', 'editPoiIcon'],
            ],
            'editheader' => [
                'href' => 'act=edit',
                'icon' => 'header.gif',
                'attributes' => 'class="edit-header"',
            ],
            'copy' => [
                'href' => 'act=paste&amp;mode=copy',
                'icon' => 'copy.gif',
            ],
            'cut' => [
                'href' => 'act=paste&amp;mode=cut',
                'icon' => 'cut.gif',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ],
            'toggle' => [
                'icon' => 'visible.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => ['oneup_contao_points_of_interest.dca_helper', 'togglePoiIcon'],
            ],
            'show' => [
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],

    'palettes' => [
        '__selector__' => ['addLink'],
        'default' => '
            {title_legend},title;
            {configuration_legend},position;
            {link_legend},addLink;
            {expert_legend},cssId,cssClass;
            {publish_legend},published,start,stop;
        ',
    ],

    'subpalettes' => [
        'addLink' => 'url',
    ],

    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
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
            'exclude' => true,
            'search' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'position' => [
            'inputType' => 'textarea',
            'eval' => [
                'tl_class' => 'clr',
                'allowHtml' => true,
                'rte' => false,
                'helpwizard' => false,
            ],
            'wizard' => [
                ['oneup_contao_points_of_interest.dca_helper', 'showPositionPicker'],
            ],
            'sql' => "mediumtext NULL",
        ],
        'cssID' => [
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
            'exclude' => true,
            'filter' => true,
            'flag' => 1,
            'inputType' => 'checkbox',
            'eval' => ['doNotCopy' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'start' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'stop' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'addLink' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'url' => [
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'rgxp' => 'url', 'decodeEntities' => true, 'maxlength' => 255, 'dcaPicker' => true, 'addWizardClass' => false, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
    ],
];
