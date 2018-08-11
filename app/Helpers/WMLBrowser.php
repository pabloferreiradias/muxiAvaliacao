<?php
namespace App\Helpers;

use Carbon\Carbon;

class WMLBrowser
{
    const PAN_NUMBERS = 6;
    const PIN_SIZE = 4;
    const START_SERVICE = 4;
    const SERVICE_LENGTH = 3;
    const START_DISCRETIONARY = 7;    
    const MIN_RANGE = 400000;
    const MAX_RANGE = 459999;
    const DATE_FORMAT = 'YYMM';

    //PAN=XXXXYYYZZZZZZ

    public static function getVar($track, $code)
    {
        $parts = explode("|", $code);
        
        if ($track == 'track1' && !empty($parts[1])) {
            return $parts[0];
        }

        if ($track == 'track2' && !empty($parts[1])) {
            return $parts[1];
        }

        if ($track == 'track2' && empty($parts[1])) {
            return $parts[0];
        }

        return false;
    }

    public static function validatePan($track)
    {
        $result = (int)substr($track, 0, self::PAN_NUMBERS);

        if ($result >= self::MIN_RANGE && $result <= self::MAX_RANGE) {
            return true;
        }

        return false;
    }

    public static function validateService($track)
    {
        $trackParts =self::explodeTrack($track);

        $service = (int)substr($trackParts[1], self::START_SERVICE, self::SERVICE_LENGTH);

        if ($service % 2 != 0) {
            return true;
        }

        return false;
    }

    public static function validateDiscretionaryData($track)
    {
        $trackParts =self::explodeTrack($track);

        $data = (int)substr($trackParts[1], self::START_DISCRETIONARY, 1);

        if ($data == 1) {
            return true;
        }

        return false;
    }

    public static function validateDate($track)
    {
        $trackParts =self::explodeTrack($track);

        $dateString = substr($trackParts[1], 0, strlen(self::DATE_FORMAT));

        try {
           $date = Carbon::createFromFormat('ymd', $dateString.'01')->endOfMonth();
        } catch (\Exception $ex) {
            return false;            
        }

        $now = Carbon::now();
        if ($date->gte($now)) {
            return true;
        }

        return false;
    }

    public static function getCardholder($track)
    {
        $trackParts = explode("^", $track);

        return $trackParts[1];
    }

    private static function explodeTrack($track)
    {
        return explode("=", $track);
    }
    
}
