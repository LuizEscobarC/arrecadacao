<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Support\Filters\FilterMoviment;

class Moviment extends Model
{
    public function __construct()
    {
        parent::__construct('moviment', ['created_at', 'id', 'updated_at'],
            [
                'id_hour',
                'id_store',
                'date_moviment',
                'beat_value',
                'last_value',
                'get_value',
                'new_value'
            ]);
    }

    public function bootstrap
    (
        string $dateMoviment,
        string $idStore,
        string $idHour,
        ?string $idList,
        string $beatValue,
        string $payingNow,
        string $expend,
        string $lastValue,
        string $getValue,
        string $newValue,
        ?string $prize,
        ?string $beatPrize,
        ?string $prizeStore,
        ?string $prizeOffice
    ) {
        $this->date_moviment = $dateMoviment;
        $this->id_store = $idStore;
        $this->id_hour = $idHour;
        $this->id_list = $idList;
        $this->beat_value = $beatValue;
        $this->paying_now = $payingNow;
        $this->expend = $expend;
        $this->last_value = $lastValue;
        $this->get_value = $getValue;
        $this->new_value = $newValue;
        $this->prize = $prize;
        $this->beat_prize = $beatPrize;
        $this->prize_store = $prizeStore;
        $this->prize_office = $prizeOffice;
        return $this;
    }

    public function hour(int $hour = null): ?Hour
    {
        if (!empty($hour)) {
            return (new Hour())->findById($hour);
        } elseif ($this->id_hour) {
            return (new Hour())->findById($this->id_hour);
        }
        return null;
    }

    public function lists(int $list = null): ?Lists
    {
        if (!empty($list)) {
            return (new Lists())->findById($list);
        } elseif ($this->id_list) {
            return (new Lists())->findById($this->id_list);
        }
        return null;
    }

    public function store(int $store = null): ?Store
    {
        if (!empty($store)) {
            return (new Store())->findById($store);
        } elseif ($this->id_store) {
            return (new Store())->findById($this->id_store);
        }
        return null;
    }

    public function requiredMoviment(?array $data): ?string
    {
        $fields = [];
        foreach (static::$required as $field) {
            if (empty($data[$field]) && !($data[$field] === 0 || $data[$field] === '0')) {
                $fields[] = $field;
            }
        }
        if (!empty($fields)) {
            foreach ($fields as $value) {
                switch ($value) {
                    case 'id_hour':
                        $fildsArray[] = 'Horário';
                        break;
                    case 'id_store':
                        $fildsArray[] = 'Nome da Loja';
                        break;
                    case 'date_moviment':
                        $fildsArray[] = 'Data de movimento';
                        break;
                }
            }
            $message = 'Os seguintes campos são necessários: ' . implode(', ', $fildsArray) . ".";
            return $this->message->warning($message)->render();
        } else {
            return null;
        }
    }

    public function isRepeated(string $dateMoviment, int $hour, int $store): bool
    {
        if (!empty($this->findByDateMoviment($dateMoviment, $hour, $store))) {
            return false;
        }
        return true;
    }

    protected function findByDateMoviment(string $dateMoviment, int $id_hour, int $id_store): ?Moviment
    {
        $dateMoviment = date_fmt_app($dateMoviment);
        (new Moviment());
        return $this->find("DATE(date_moviment) = '{$dateMoviment}' AND id_store = {$id_store} AND id_hour = {$id_hour}",
            null, 'id')->fetch();
    }

    public function isEmpty(array &$data): void
    {
        $dataEmpty = [
            'paying_now',
            'expend',
            'get_value',
            'beat_value',
            'new_value',
            'prize',
            'beat_prize',
            'prize_store',
            'prize_office',
        ];

        if (empty($data[$dataEmpty[0]])) {
            $data[$dataEmpty[0]] = 0;
        }

        if (empty($data[$dataEmpty[1]])) {
            $data[$dataEmpty[1]] = 0;
        }

        if (empty($data[$dataEmpty[0]]) && empty($data[$dataEmpty[1]])) {
            $data[$dataEmpty[2]] = 0;
            $data[$dataEmpty[3]] = (!empty($data['id_list']) ? -abs($this->lists($data['id_list'])->net_value) : ($data['id_list'] = 0));
            if ($this->store($data['id_store'])->valor_saldo) {
                $data[$dataEmpty[4]] = ((float)($this->store($data['id_store'])->valor_saldo) + $data[$dataEmpty[3]]);
            }
        }
        if (empty($data[$dataEmpty[5]])) {
            $data[$dataEmpty[5]] = 0;
            $data[$dataEmpty[6]] = 0;
            $data[$dataEmpty[7]] = 0;
            $data[$dataEmpty[8]] = 0;
        }
    }

    public function filter(array $data): array
    {
        if (!empty($data['page'])) {
            array_pop($data);
        }
        // Se os filtros não foram submitados o padrão é adicionado a classe
        if (!empty($data)) {
            //formatando se existir
            if (!empty($data['search_date'])) {
                $date = str_replace('/', '-', $data['search_date']);
                $data['search_date'] = "DATE('" . date_fmt_app($date) . "')";
            }
            $filters = $data;
        } else {
            $filters = ['search_store' => '', 'search_hour' => '', 'search_date' => ''];
        }
        // passo o modelo e os filtros vazios ou não
        $filterClass = new FilterMoviment($this, $filters);

        $arrayFilterReturn = $filterClass->where([
            'search_store' => 'like',
            'search_hour' => 'like',
            'search_date' => 'equal'
        ],
            [
                'search_store' => 's.nome_loja',
                'search_hour' => 'h.description',
                'search_date' => "DATE(moviment.date_moviment)"
            ])
            ->find(['moviment.*', 'h.week_day', 'h.number_day', 'h.description as hour', 's.nome_loja']);

        $total = $filterClass->total([
            'new_value' => 'total_new_value',
            'beat_value' => 'total_beat_value',
            'paying_now' => 'total_paying_now',
            'expend' => 'total_expend',
            'get_value' => 'total_get_value',
            'last_value' => 'total_last_value',
            'l.net_value' => 'total_net_value',
            'l.comission_value' => 'total_comission_value',
            'l.total_value' => 'total_total_value'
        ], new Moviment());

        return array_merge($arrayFilterReturn, [$total]);
    }
}