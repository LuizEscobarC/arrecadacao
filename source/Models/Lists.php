<?php

namespace Source\Models;

use Source\Core\Model;

class Lists extends Model
{
    public function __construct()
    {
        parent::__construct('lists', ['id', 'created_at', 'updated_at'],
            ['id_hour', 'id_store', 'total_value', 'comission_value', 'net_value', 'date_moviment']);
    }

    public function bootstrap(
        string $descriptionHour,
        string $idStore,
        string $totalValue,
        string $dateMoviment
    ): Lists {
        $this->id_hour = $descriptionHour;
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

    public function listFilters(?array $data): array
    {
        $search['search_store'] = filter_var((!empty($data['search_store']) ? $data['search_store'] : null),
            FILTER_SANITIZE_STRIPPED);
        $search['search_hour'] = filter_var((!empty($data['search_hour']) ? $data['search_hour'] : null),
            FILTER_SANITIZE_STRIPPED);
        $search['search_date'] = filter_var((!empty($data['search_date']) ? $data['search_date'] : null),
            FILTER_SANITIZE_STRIPPED);

        if ($search['search_store'] || $search['search_hour'] || $search['search_date']) {
            if ($search['search_store']) {
                $where[] = "s.nome_loja LIKE '%{$search['search_store']}%'";
            } else {
                $search['search_store'] = null;
            }
            if ($search['search_hour']) {
                $where[] = "h.description LIKE '%{$search['search_hour']}%'";
            } else {
                $search['search_hour'] = null;
            }
            if ($search['search_date']) {
                $date = str_replace('/', '-', $search['search_date']);
                $date = date_fmt_app($date);
                $where[] = "lists.date_moviment = '{$date}'";
            } else {
                $search['search_date'] = null;
            }

            $where = implode(' AND ', $where);

            // para pegar o totalizador de valor dinâmico com filtro
            $total = clone $this->find(null, null, 'lists.*, h.week_day , h.description, s.nome_loja, sum(total_value) as total')
                ->join('hour h', 'lists.id_hour', 'h.id')
                ->join('loja s', 'lists.id_store', 's.id');

            $total->putQuery($where, ' WHERE ');

            $this->find(null, null, 'lists.*, h.week_day , h.description, s.nome_loja')
                ->join('hour h', 'lists.id_hour', 'h.id')
                ->join('loja s', 'lists.id_store', 's.id');

            $this->putQuery($where, ' WHERE ');

        } else {
             $total = clone $this->find(null, null, 'sum(total_value) as total')
                 ->join('hour h', 'lists.id_hour', 'h.id')
                 ->join('loja s', 'lists.id_store', 's.id');

             $this->find(null, null, 'lists.*, h.week_day , h.description, s.nome_loja')
                ->join('hour h', 'lists.id_hour', 'h.id')
                ->join('loja s', 'lists.id_store', 's.id');

            $search['search_store'] = null;
            $search['search_hour'] = null;
            $search['search_date'] = null;
        }
        return [$this , $search, $total];
    }



}