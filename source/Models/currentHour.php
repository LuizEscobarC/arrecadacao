<?php

namespace Source\Models;

use Source\Core\Model;

class currentHour extends Model
{

    public function __construct()
    {
        parent::__construct('hour_settings', ['id'], []);
    }

    public function hour(int $hour = null): ?Hour
    {
        if (!empty($hour)) {
            return (new Hour())->findById($hour);
        } elseif ($this->current_hour) {
            return (new Hour())->findById($this->current_hour);
        }
        return null;
    }

}