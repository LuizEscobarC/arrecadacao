<?php

namespace Source\Support;

use Source\Models\Hour;

class HourManager
{
    public static function getHourByDate(array $data): array
    {
        $getDayNumber = weekDay($data['date_moviment'], true);
        $dataDay = (new Hour())->findByNumberDayNotClosed($getDayNumber);
        $i = 1;

        foreach ($dataDay as $item) {
            $callback[0] = $item->week_day;
            $callback[$i]['id'] = $item->id;
            $callback[$i]['description'] = $item->description;
            $i++;
        }
        return $callback;
    }

    public static function getHoursByDate(string $date) {
        $numberDay = weekDay($date, true);
        $hours = (new Hour())->findByNumberDay($numberDay);
        return $hours;
    }

}