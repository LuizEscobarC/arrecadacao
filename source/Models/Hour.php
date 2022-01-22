<?php

namespace Source\Models;

use Source\Core\Model;

class Hour extends Model
{

    public function __construct()
    {
        parent::__construct('hour', ['id', 'created_at', 'updated_at'], ['number_day', 'week_day', 'description']);
    }

    public function bootstrap(int $numberDay, string $weekDay, string $description): Hour
    {
        $this->number_day = $numberDay;
        $this->week_day = $weekDay;
        $this->description = $description;
        return $this;
    }

}