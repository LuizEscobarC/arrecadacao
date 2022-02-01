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
}