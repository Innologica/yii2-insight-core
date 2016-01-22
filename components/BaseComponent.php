<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 21.12.2015
 * Time: 13:52 �.
 */

namespace insight\core\components;

use Yii;
use yii\base\Component;

class BaseComponent extends Component
{
    public function getSettings($key)
    {
        $value = Yii::$app->registry->get($key, Yii::$app->user->id);
        if (!$value) {
            if (isset(static::$settings[$key])) {
                return static::$settings[$key];
            }
        }
        return $value;
    }
}
