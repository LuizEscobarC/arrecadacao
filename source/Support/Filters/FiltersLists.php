<?php

namespace Source\Support\Filters;

use Source\Models\MoldelInterfaces\FilterInterface;

class FiltersLists extends FilterQuery implements FilterInterface
{
    protected $model;

    public function listFilters(?array $data): array
    {
        $search = $this->filterSanitaze($data);
        $where = false;
        if (!empty($search)) {
            if (!empty($search['search_store'])) {
                $this->like('s.nome_loja', $search['search_store']);
            }
            if (!empty($search['search_hour'])) {
                $this->like('h.description', $search['search_hour']);
            }
            if (!empty($search['search_date'])) {
                $date = str_replace('/', '-', $search['search_date']);
                $date = date_fmt_app($date);
                $this->equal("DATE(lists.date_moviment)", "DATE('{$date}')");
            }
            $where = $this->implode();

        } else {
            $search['search_store'] = null;
            $search['search_hour'] = null;
            $search['search_date'] = null;
        }
        // para pegar o totalizador de valor dinâmico com filtro
        $total = clone $this->model->find(null, null, 'lists.*, h.week_day , h.description, s.nome_loja, sum(total_value) as total, sum(comission_value) 
                    as total_comission, sum(net_value) as total_net')
            ->join('hour h', 'lists.id_hour', 'h.id')
            ->join('loja s', 'lists.id_store', 's.id');

        if ($where) {
            $total->putQuery($where, ' WHERE ');
        }

        $this->model->find(null, null, 'lists.*, h.week_day , h.description, s.nome_loja')
            ->join('hour h', 'lists.id_hour', 'h.id')
            ->join('loja s', 'lists.id_store', 's.id');

        if ($where) {
            $this->model->putQuery($where, ' WHERE ');
        }
        return [$this->model, $search, $total];
    }
}