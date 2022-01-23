<?php

namespace App\Service;

class Time
{
  public static function getCurrentTime()
  {
    return new \DateTimeImmutable();
  }

  public static function getCurrentTimeAsString()
  {
    return Time::getCurrentTime()->format('Y-m-d H:i:s');
  }

  public static function getHoursSince($time)
  {
    $now = Time::getCurrentTime();
    $diff = $now->diff($time);
    return $diff->h;
  }

  public static function getHoursFromSeconds($seconds)
  {
    return floor($seconds / 3600);
  }

  public static function getMinutesFromSeconds($seconds)
  {
    return floor(($seconds / 60) % 60);
  }

  public static function getHoursMinutesSeconds($seconds)
  {
    $hours = Time::getHoursFromSeconds($seconds);
    $minutes = Time::getMinutesFromSeconds($seconds);
    $seconds = $seconds % 60;

    return [
      'hours' => $hours,
      'minutes' => $minutes,
      'seconds' => $seconds,
    ];
  }

  public static function getFormattedHoursMinutesSeconds($seconds)
  {
    $hoursMinutesSeconds = Time::getHoursMinutesSeconds($seconds);
    $hours = $hoursMinutesSeconds['hours'];
    $minutes = $hoursMinutesSeconds['minutes'];
    $seconds = $hoursMinutesSeconds['seconds'];

    $result = '';

    if ($hours > 0) {
      $result .= sprintf('%01d', $hours) . ':';
    }

    $result .= sprintf('%02d', $minutes) . ':';

    if ($seconds > 0) {
      $result .= sprintf('%02d', $seconds) . ':';
    } else {
      $result .= '00';
    }

    return $result;
  }

  public static function getHoursAndMinutesFromSeconds($seconds)
  {
    $hours = Time::getHoursFromSeconds($seconds);
    $minutes = Time::getMinutesFromSeconds($seconds);

    $result = '';

    if ($hours > 0) {
      $result .= sprintf('%01d', $hours) . ' h ';
    }

    $result .= sprintf('%02d', $minutes) . ' min';

    return $result;
  }
}