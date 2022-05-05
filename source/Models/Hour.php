<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Support\HourManager;

class Hour extends Model
{

    public function __construct()
    {
        parent::__construct('hour', ['id', 'created_at', 'updated_at'], ['number_day', 'week_day', 'description']);
    }

    public function bootstrap(string $numberDay, string $weekDay, string $description): Hour
    {
        $this->number_day = $numberDay;
        $this->week_day = $weekDay;
        $this->description = $description;
        return $this;
    }

    public function findByNumberDay(int $numberDay): ?array
    {
        $this->find('number_day = :number_day', "number_day={$numberDay}");
        return $this->fetch(true);
    }

    public function findByNumberDayNotClosed(int $numberDay): ?array
    {
        $this->find('number_day = :number_day AND status = 1', "number_day={$numberDay}");
        return $this->fetch(true);
    }

    public function resetStatus(): void
    {
        $numberDay = weekDay(date_fmt('now','Y-m-d'), true);
        if ($numberDay != 0) {
            --$numberDay;
        } else {
            $numberDay = 6;
        }

        // PROCURA SE EXISTE HORARIO DO DIA ANTERIOR FECHADO
        if (!$this->find('number_day = :n AND status = 0', "n=$numberDay")->fetch()) {
            return;
        }

        $this->update(['status' => 1], "number_day = :n", "n={$numberDay}");
    }
}