<?php

namespace insight\core\helpers;

/**
 * Description of ArrayHelper
 *
 * @author ntraikov
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{
    public static function find(&$array, $key)
    {
        foreach ($array as $currKey => $value) {
            if ($currKey === $key) {
                return $value;
            }
            if (is_array($value)) {
                $result = self::find($value, $key);
                if ($result) {
                    return $result;
                }
            }
        }
        return false;
    }
}