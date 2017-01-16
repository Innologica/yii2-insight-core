<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 22.12.2015
 * Time: 13:41 ч.
 */

namespace insight\core\util;

use DateInterval;
use DatePeriod;
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


    /**
     * The function returns the no. of business days between two dates and it skips the holidays
     *
     * @param $startDate
     * @param $endDate
     * @param $holidays - array of dates to exclude from calculation
     * @return float|int
     */
    public static function getWorkingDays($startDate, $endDate, $holidays = []){
        // do strtotime calculations just once
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);


        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400 + 1;

        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);

        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);

        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)

            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;

                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            }
            else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }

        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
            $workingDays += $no_remaining_days;
        }

        //We subtract the holidays
        foreach($holidays as $holiday){
            $time_stamp=strtotime($holiday);
            //If the holiday doesn't fall in weekend
            if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
                $workingDays--;
        }

        return $workingDays;
    }

    /**
     * Given two dates, creates an array of keys, representing the indexes of the days starting
     * of 1 (Monday) and the dates of each day as values.
     * 
     * For example if we have 2016-08-09 and 2016-08-10 as an input, the result will be:
     * 2 => '2016-08-08', // Tuesday
     * 3 => '2016-08-09', // Wednesday
     *
     * @param DateTime $start
     * @param DateTime $end
     */
    public static function dayOfWeekToDateMap(DateTime $start, DateTime $end)
    {
        $diff = $end->diff($start);
        if ($diff->d > 7) {
            return false;
        }

        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);
        
        $result = [];
        foreach ($dateRange as $date) {
            $result[$date->format('N')] = $date->format('Y-m-d');
        }

        return $result;
    }

    public static function fixTimezone($date)
    {
        if (is_string($date)) {
            $date = new DateTime($date, new DateTimeZone('UTC'));
        }
        $date->setTimezone(new DateTimeZone(Yii::$app->timeZone));
        return $date;
    }

    public static function getWorkingHours($startTime, $endTime)
    {
        $start = new \DateTime($startTime);
        $end = new \DateTime($endTime);

        $interval = $start->diff($end);
        if ($interval->i == 0) {
            return $start->diff($end)->format('%h hours');
        }

        return $start->diff($end)->format('%h hours %i minutes');
    }
}
