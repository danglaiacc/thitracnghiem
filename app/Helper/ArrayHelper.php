<?php

namespace App\Helper;

class ArrayHelper
{
    public static function transformCollectionsWithIdAsKey($items, $key = 'id')
    {
        $result = [];
        foreach ($items as $item) {
            $result[$item->$key] = $item;
        }

        return $result;
    }
}
