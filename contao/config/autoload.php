<?php

$templatesFolder = version_compare(VERSION, '4.0', '>=')
    ? 'vendor/oneup/contao-points-of-interest/templates'
    : 'system/modules/points-of-interest/templates';

TemplateLoader::addFiles([
    'poi_default' => $templatesFolder,
]);
