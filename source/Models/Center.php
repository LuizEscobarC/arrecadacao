<?php

namespace Source\Models;

use Source\Core\Model;

class Center extends Model
{
    public function __construct()
    {
        parent::__construct('cost', ['id', 'created_at', 'updated_at'], ['description', 'emit']);
    }
}