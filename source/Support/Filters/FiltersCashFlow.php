<?php

namespace Source\Support\Filters;

use Source\Core\Model;
use Source\Models\CashFlow;
use Source\Models\MoldelInterfaces\FilterInterface;
use Source\Models\Moviment;

class FiltersCashFlow extends Filter
{

    public function find(array $columns): array
    {
        foreach ($columns as $column) {
            $arraySelects[] = "{$column}";
        }
        $select = implode(', ', $arraySelects);

        $this->model->find(null, null, $select)
            ->join('hour h', 'h.id', 'cash_flow.id_hour')
            ->join('loja s', 's.id', 'cash_flow.id_store')
            ->join('cost cc', 'cc.id', 'cash_flow.id_cost', 'LEFT');

        if ($this->implode) {
            $this->model->putQuery($this->implode, ' WHERE ');
        }
        return [$this->model, $this->filters];
    }

    public function total(array $columnsAndAliases, Model $model = null)
    {
        foreach ($columnsAndAliases as $column => $alias) {
            $arraySelects[] = "sum({$column}) as {$alias}";
        }
        $select = implode(', ', $arraySelects);
        // para pegar o totalizador de valor dinâmico com filtro

        $total = ((!empty($model) ? $model : $this->model))->find(null, null,
            "(SELECT {$select} FROM cash_flow c 
                    inner join hour h on h.id = c.id_hour 
                    inner join loja s on s.id = c.id_store
                    left join cost cc on cc.id = c.id_cost WHERE type = 1 " . ($this->implode ? "AND {$this->implode} " : " ") . ") AS income,
                 (SELECT {$select} FROM cash_flow c 
                    join hour h on h.id = c.id_hour 
                    join loja s on s.id = c.id_store
                    left join cost cc on cc.id = c.id_cost WHERE type = 2 " . ($this->implode ? "AND {$this->implode} " : " ") . ") AS expense"
        );


        if ($total = $total->fetch()) {
            if (empty($total->income) && !empty($total->expense)) {
                $total->total = -abs($total->expense);
            } else {
                $total->total = $total->income - $total->expense;
            }
            $total->expense = (!empty($total->expense) ? -abs($total->expense) : null);
        } else {
            $total->total = null;
        }

        return $total;
    }
}