<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * CLASSE DA TABELA CASH-FLOW
 */
class CashFlow extends Model
{
    /**
     * CONSTRUTOR
     */
    public function __construct()
    {
        parent::__construct('cash_flow', ['id', 'created_at', 'updated_at'],
            ['date_moviment', 'id_store', 'id_hour', 'description', 'value', 'type']);
    }

    /**
     * @param string $dateMoviment
     * @param int $idStore
     * @param int $idHour
     * @param string $description
     * @param string $value
     * @param int $type
     * @param int|null $idCost
     * @return $this
     */
    public function bootstrap(
        string $dateMoviment,
        int $idStore,
        int $idHour,
        string $description,
        string $value,
        int $type,
        ?string $idCost
    ): CashFlow {
        $this->date_moviment = $dateMoviment;
        $this->id_store = $idStore;
        $this->id_hour = $idHour;
        $this->description = $description;
        $this->value = $value;
        $this->type = $type;
        $this->id_cost = $idCost;
        return $this;
    }

    /**
     * RETURN HOUR FOREIGN KEY VALUES
     * @return Hour|null
     */
    public function hour(): ?Hour
    {
        $hour = (new Hour())->findById($this->id_hour);
        if (isnt_empty($hour)) {
            return $hour;
        }
        return null;
    }

    /**
     * RETURN STORE FOREIGN KEY VALUES
     * @return mixed|Model|null
     */
    public function store()
    {
        $store = (new Store())->findById($this->id_store);
        if (isnt_empty($store)) {
            return $store;
        }
        return null;
    }

    /**
     * RETURN COSTCENTER FOREIGN KEY VALUES
     * @return mixed|Model|null
     */
    public function cost()
    {
        $cost = (new Center())->findById($this->id_cost);
        if (isnt_empty($cost)) {
            return $cost;
        }
        return null;
    }
}