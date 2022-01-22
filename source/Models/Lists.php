<?php

namespace Source\Models;

use Source\Core\Model;

class Lists extends Model
{
    public function __construct()
    {
        parent::__construct('lists', ['id', 'created_at', 'updated_at'],
            ['id_hour', 'id_store', 'total_value', 'comission_value', 'net_value']);
    }

    public function bootstrap(
        int $descriptionHour,
        int $idStore,
        float $totalValue,
        float $comissionValue,
        float $netValue
    ): Lists {
        $this->id_hour = $descriptionHour;
        $this->id_store = $idStore;
        $this->total_value = $totalValue;
        $this->comission_value = $comissionValue;
        $this->net_value = $netValue;
        return $this;
    }

    public function hour(): ?Hour
    {
        if ($this->id_hour) {
            return (new Hour())->findById($this->id_hour);
        }
        return null;
    }

    public function store(): ?Store
    {
        if ($this->id_store) {
            return (new Store())->findById($this->id_store);
        }
        return null;
    }
}