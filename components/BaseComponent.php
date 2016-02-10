<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 21.12.2015
 * Time: 13:52 �.
 */

namespace insight\core\components;

use Yii;
use yii\base\Component;

/**
 * Common functionalities to all Insight components.
 */
class BaseComponent extends Component
{
    /**
     * Finds a setting by its id.
     *
     * The settings for all modules must be declared in the module's core component like this:
     * ```php
     * public static $settings = [
     *      'settingKey1' => 'Value 1',
     *      'settingKey2' => 'Value 2',
     *      ...
     * ];
     * ```
     *
     * @param string $key The key of the setting.
     * @param integer $userId A user's id. By default is NULL.
     * @return string|bool The setting if found, false if not.
     */
    public function getSettings($key, $userId = null)
    {
        $value = Yii::$app->registry->get($key, $userId);
        if (!isset($value) && isset(static::$settings[$key])) {
            return static::$settings[$key];
        }
        return $value;
    }
}
