<?php

namespace Source\Support\Filters;

use Source\Models\MoldelInterfaces\FilterInterface;

class FiltersMoviment extends FilterQuery implements FilterInterface
{
    protected $model;

    public function listFilters(?array $data): array
    {
        $search = $this->filterSanitaze($data);
        $where = false;
        if (!empty($search)) {
            if (!empty($search['search_store'])) {
                $this->like("s.nome_loja", $search['search_store']);
            }
            if (!empty($search['search_hour'])) {
                $this->like("h.description", $search['search_hour']);
            }
            if (!empty($search['search_date'])) {
                $date = str_replace('/', '-', $search['search_date']);
                $date = date_fmt_app($date);
                $this->equal("DATE(moviment.date_moviment)", "DATE('{$date}')");
            }
            $where = $this->implode();
        } else {
            $search['search_date'] = null;
            $search['search_hour'] = null;
            $search['search_store'] = null;
        }

        // para pegar o totalizador de valor dinâmico com filtro
        $total = clone $this->model->find(null, null,
            'sum(new_value) as total_new_value, sum(beat_value) as
             total_beat_value, sum(paying_now) as total_paying_now, 
             sum(expend) as total_expend, sum(get_value) as total_get_value,
              sum(last_value) as total_last_value')
            ->join('hour h', 'moviment.id_hour', 'h.id')
            ->join('loja s', 'moviment.id_store', 's.id');

        if ($where) {
            $total->putQuery($where, ' WHERE ');
        }

        $this->model->find(null, null,
            'moviment.*, h.week_day, h.number_day, h.description as hour, s.nome_loja')
            ->join('hour h', 'h.id', 'moviment.id_hour')
            ->join('loja s', 's.id', 'moviment.id_store');

        if ($where) {
            $this->model->putQuery($where, ' WHERE ');
        }

        if ($total = $total->fetch()) {
            return [$this->model, $search, $total];
        } else {
            return [];
        }
    }
}