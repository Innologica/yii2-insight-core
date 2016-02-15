<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 22.12.2015
 * Time: 13:41 �.
 */

namespace insight\core\util;

use DateTime;
use DateTimeZone;
use Yii;
use yii\base\Object;

class DateTimeUtil extends Object
{
    /**
     * List all time zones grouped by region. Useful for dropdown items fill.
     *
     * @return array
     */
    public static function listTimeZone()
    {
        $regions = array(
            'Africa' => DateTimeZone::AFRICA,
            'America' => DateTimeZone::AMERICA,
            'Antarctica' => DateTimeZone::ANTARCTICA,
            'Asia' => DateTimeZone::ASIA,
            'Atlantic' => DateTimeZone::ATLANTIC,
            'Europe' => DateTimeZone::EUROPE,
            'Indian' => DateTimeZone::INDIAN,
            'Pacific' => DateTimeZone::PACIFIC
        );

        $timezones = array();
        foreach ($regions as $name => $mask) {
            $zones = DateTimeZone::listIdentifiers($mask);
            foreach ($zones as $timezone) {
                // Lets sample the time there right now
                $time = new DateTime(NULL, new DateTimeZone($timezone));

                // Us dumb Americans can't handle millitary time
                $ampm = $time->format('H') > 12 ? ' (' . $time->format('g:i a') . ')' : '';

                // Remove region name and add a sample time
                $timezones[$name][$timezone] = substr($timezone, strlen($name) + 1) . ' - ' . $time->format('H:i') . $ampm;
            }
        }
        return $timezones;
    }

    public static function toDate($date, $format = 'yyyy-MM-dd')
    {
        return Yii::$app->formatter->asDate($date, $format);
    }

    public static function listWeekdays()
    {
        Yii::$app->language = 'ru';
        $timestamp = strtotime('next Sunday');
        $days = array();
        for ($i = 0; $i < 7; $i++) {
            $days[$i] = Yii::$app->formatter->asDate($timestamp, 'EEEE');
            $timestamp = strtotime('+1 day', $timestamp);
        }

        return $days;
    }
}