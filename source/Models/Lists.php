<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\MoldelInterfaces\FilterInterface;

class Lists extends Model
{
    public function __construct()
    {
        parent::__construct('lists', ['id', 'created_at', 'updated_at'],
            ['id_hour', 'id_store', 'total_value', 'comission_value', 'net_value', 'date_moviment']);
    }

    public function bootstrap(
        int $idHour,
        int $idStore,
        string $totalValue,
        string $dateMoviment
    ): Lists {
        $this->id_hour = $idHour;
        $this->id_store = $idStore;
        $this->total_value = $totalValue;
        $this->date_moviment = $dateMoviment;
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
            (new $this);
            return (new Store())->findById($this->id_store);
        }
        return null;
    }

    public function save(): bool
    {
        $this->comission_value = (($this->total_value * $this->store()->comissao) / 100);
        (new Lists());
        $this->net_value = $this->total_value - $this->comission_value;
        return parent::save(); // TODO: Change the autogenerated stub
    }

    public function filter(FilterInterface $filter, array $data): array
    {
        return $filter->listFilters($data);
    }

    public function findByStoreHour(int $idStore, int $idHour): ?\stdClass
    {
        $this->find('id_hour = :h AND id_store = :s',
            "&h={$idHour}&s={$idStore}");
        if (empty($this->fetch())) {
            $this->message->error('Não existe uma lista cadastrada para a loja e o horário especificado.')->flash();
            return null;
        }
        return $this->fetch()->data();
    }


}