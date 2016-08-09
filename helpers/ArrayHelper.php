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

    public static function arrayFill($start_index, $num, $value)
    {
        if (is_object($value)) {
            $result = [];
            for ($i = $start_index; $i < $num; $i++) {
                $result[$i] = clone $value;
            }
            return $result;
        }
        return array_fill($start_index, $num, $value);
    }
}