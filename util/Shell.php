<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 20.05.2016
 * Time: 09:02 ч.
 */

namespace insight\core\util;

use yii\helpers\Json;

class Shell
{
    public static function launch($call)
    {
        // Windows
        if(self::isWindows()) {
            pclose(popen("start $call", "r"));
        } else { // Some sort of UNIX
            //pclose(popen($call." /dev/null &", "r"));
            exec($call . " > /dev/null &");
        }
        return true;
    }

    public static function exec($call, $output = false)
    {
        if ($output) {
            $result = [];
            exec($call, $result);
            // The output of the command (if any) is stored as a first element of the array
            return !empty($result) ? Json::decode($result[0]) : [];
        }
        return exec($call);
    }

    public static function isWindows()
    {
        return PHP_OS == 'WINNT' || PHP_OS == 'WIN32';
    }
}
