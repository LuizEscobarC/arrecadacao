<?php

namespace Source\Models;

use Composer\Package\Loader\ValidatingArrayLoader;
use Source\Core\Model;
use Source\Support\Filters\FiltersLists;

class SelfList extends Model
{
    public function __construct()
    {
        parent::__construct('list', ['id', 'created_at', 'updated_at'], ['date_moviment', 'id_store', 'id_hour', 'id_lists', 'value']);
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

    public function lists(): ?Lists
    {
        if ($this->id_lists) {
            return (new Lists())->findById($this->id_lists);
        }
        return null;
    }

    public static function requiredData($data)
    {
        $required = new Lists();
        if (!$required->requiredList($data)) {
            return false;
        }
        return true;
    }

    public function saveRoutine(): bool
    {

        // CURRENT HOUR SETTINGS IN DB
        Moviment::saveCurrentHour($this->id_hour);

        $lists = (new Lists())->findByStoreHour($this->id_store, $this->id_hour, $this->date_moviment, false);
        // SE TIVER PAI SÓ ATUALIZA A PAI
        if (!empty($lists)) {
            $this->id_lists = $lists->id;
        } else {
            $lists = new Lists();
        }

        // DADOS PARA CADASTRO
        $lists->date_moviment = $this->date_moviment;
        $lists->id_store = $this->id_store;
        $lists->id_hour = $this->id_hour;
        $lists->total_value = $this->value;

        if (!$lists->save()) {
            return false;
        }

        $this->id_lists = $lists->id;

        if (!$this->save()) {
            return false;
        }

        // CALCULA AS LISTAS
        SelfList::calc($this);
        return true;
    }

    public function filter(array $data): array
    {
        if (!empty($data['page'])) {
            array_pop($data['page']);
        }

        if (!empty($data)) {
            if (!empty($data['search_date'])) {
                $date = str_replace('/', '-', $data['search_date']);
                $data['search_date'] = "DATE('" . date_fmt($date, 'Y-m-d') . "')";
            }
            $filters = $data;
        } else {
            $filters = ['search_store' => '', 'search_hour' => '', 'search_date' => ''];
        }

        $filterClass = new FiltersLists($this, $filters);

        $arrayFilterReturn = $filterClass->where([
            'search_store' => 'like',
            'search_hour' => 'like',
            'search_date' => 'equal'
        ],
            [
                'search_store' => 's.nome_loja',
                'search_hour' => 'h.description',
                'search_date' => 'DATE(list.date_moviment)'
            ])
            ->find([
                'list.*',
                'h.week_day',
                'h.description',
                's.nome_loja'
            ]);

        $total = $filterClass->total([
            'total_value' => 'total',
            'comission_value' => 'total_comission',
            'net_value' => 'total_net'
        ], new Lists());
        return array_merge($arrayFilterReturn, [$total]);
    }

    public static function calc(SelfList $list): void
    {
        $listsValues = (new SelfList())->find("id_store = {$list->id_store} AND id_hour = {$list->id_hour} AND DATE(date_moviment) = {DATE('$list->date_moviment')}",
            null, 'value')->fetch(true);
        if (!empty($listsValues)) {
            $totalValue = 0;
            foreach ($listsValues as $listIterator) {
                $totalValue += $listIterator->value;
            }
            $lists = $list->lists();
            $lists->total_value = $totalValue;
            $lists->save();
        }
    }

}