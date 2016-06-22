<?php

namespace insight\core\util;

use yii\helpers\Json;

/**
 * @author Nikolay Traykov
 */
class GeoNames
{
    const BASE_URL = 'http://api.geonames.org/';
    const USERNAME = 'innologica';

    public static function timezone($lat, $lng)
    {
        if (!$lat) {
            return null;
        }
        
        $url = self::BASE_URL . 'timezoneJSON?username='.self::USERNAME."&lat=$lat&lng=$lng";
        $result = self::sendRequest($url);
        if (isset($result['status'])) { // Status is returned only when an error exists
            return null;
        }
        return $result;
    }

    public static function countryInfo($countryCode)
    {
        if (!$countryCode) {
            return null;
        }
        
        $url = self::BASE_URL . 'countryInfoJSON?username='.self::USERNAME."&country=$countryCode";
        $result = self::sendRequest($url);
        if (!empty($result['geonames'])) { // 'geonames' is returned every time. It may be empty or not
            return $result['geonames'][0];
        }
        return null;
    }

    private static function sendRequest($url)
    {
        return Json::decode(file_get_contents($url));
    }
}
