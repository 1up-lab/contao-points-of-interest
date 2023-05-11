<?php

declare(strict_types=1);

namespace Oneup\Contao\ContaoPointsOfInterestBundle\Model;

use Contao\Date;
use Contao\Model;

class PointOfInterestModel extends Model
{
    protected static $strTable = 'tl_point_of_interest';

    public static function findPublishedByPid($intPid, array $arrOptions = [])
    {
        $t = static::$strTable;
        $arrColumns = ["$t.pid=?"];

        if (!static::isPreviewMode($arrOptions)) {
            $time = Date::floorToMinute();
            $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";
        }

        return static::findBy($arrColumns, $intPid, $arrOptions);
    }
}
