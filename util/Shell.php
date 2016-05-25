<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 20.05.2016
 * Time: 09:02 ч.
 */

namespace insight\core\util;


class Shell
{

    public static function launch($call) {
        // Windows
        if(BackgroundProcess::is_windows()){
            pclose(popen("start /b $call", "r"));
        } else { // Some sort of UNIX
            pclose(popen($call." /dev/null &", "r"));
        }
        return true;
    }

    public static function is_windows(){
        if(PHP_OS == 'WINNT' || PHP_OS == 'WIN32'){
            return true;
        }
        return false;
    }

}