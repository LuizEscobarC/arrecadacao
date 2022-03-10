<?php

namespace Source\Support\Filters;

use Source\Models\MoldelInterfaces\FilterInterface;

class FiltersCashFlow extends FilterQuery implements FilterInterface
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
                $this->equal("DATE(cash_flow.date_moviment)", "DATE('{$date}')");
            }
            $where = $this->implode();
        } else {
            $search['search_date'] = null;
            $search['search_hour'] = null;
            $search['search_store'] = null;
        }

        // para pegar o totalizador de valor dinâmico com filtro
        $total = clone $this->model->find(null, null,
            "(SELECT SUM(value) FROM cash_flow c 
                    inner join hour h on h.id = c.id_hour 
                    inner join loja s on s.id = c.id_store
                    left join cost cc on cc.id = c.id_cost WHERE type = 1 " . ($where ? "AND {$where} " : " ") . ") AS income,
                 (SELECT SUM(value) FROM cash_flow c 
                    join hour h on h.id = c.id_hour 
                    join loja s on s.id = c.id_store
                    left join cost cc on cc.id = c.id_cost WHERE type = 2 " . ($where ? "AND {$where} " : " ") . ") AS expense"
        );

        $this->model->find(null, null,
            'cash_flow.*, h.week_day, h.number_day, h.description as hour, s.nome_loja, cc.description as cost')
            ->join('hour h', 'h.id', 'cash_flow.id_hour')
            ->join('loja s', 's.id', 'cash_flow.id_store')
            ->join('cost cc', 'cc.id', 'cash_flow.id_cost', 'LEFT');

        if($where) {
            $this->model->putQuery($where, ' WHERE ');
        }

        if ($total = $total->fetch()) {
            if (empty($total->income) && !empty($total->expense)) {
                $total = -abs($total->expense);
            } else {
                $total = $total->income - $total->expense;
            }
        } else {
            $total = null;
        }
        return [$this->model, $search, $total];
    }
}