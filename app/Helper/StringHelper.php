<?php

namespace App\Helper;

class StringHelper
{
    /**
     * @param object new User(['name' => 'name]);
     */
    public static function getSqlQuery($object)
    {
        return vsprintf(str_replace('?', '%s', $object->toSql()), $object->getBindings());
    }
}
