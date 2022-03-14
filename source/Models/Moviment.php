<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Models\MoldelInterfaces\FilterInterface;
use Source\Support\Filters\FiltersMoviment;

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
                'paying_now',
                'expend',
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
        string $beatValue,
        string $payingNow,
        string $expend,
        string $lastValue,
        string $getValue,
        string $newValue,
        string $prize,
        string $beatPrize
    ) {
        $this->date_moviment = $dateMoviment;
        $this->id_store = $idStore;
        $this->id_hour = $idHour;
        $this->beat_value = $beatValue;
        $this->paying_now = $payingNow;
        $this->expend = $expend;
        $this->last_value = $lastValue;
        $this->get_value = $getValue;
        $this->new_value = $newValue;
        $this->prize = $prize;
        $this->beat_prize = $beatPrize;
        return $this;
    }

    public function hour(): ?Hour
    {
        if ($this->id_hour) {
            (new $this);
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

    public function filter(array $data): array
    {
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
        return (new FiltersMoviment($this))->listFilters($filters, ['like', 'like', 'equal'],
            [
                'search_store' => 's.nome_loja',
                'search_hour' => 'h.description',
                'search_date' => "DATE(moviment.date_moviment)"
            ]);
    }
}