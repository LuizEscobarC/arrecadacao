<?php

namespace Source\Support\Filters;

use Source\Core\Model;

class FilterMoviment extends Filter
{
    public function find(array $columns): array
    {
        foreach ($columns as $column) {
            $arraySelects[] = "{$column}";
        }
        $select = implode(', ', $arraySelects);

        $this->model->find(null, null, $select)
            ->join('hour h', 'h.id', 'moviment.id_hour')
            ->join('loja s', 's.id', 'moviment.id_store');
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
        $total = ((!empty($model) ? $model : $this->model))->find(null, null, $select)
            ->join('hour h', 'h.id', 'moviment.id_hour')
            ->join('loja s', 's.id', 'moviment.id_store')
            ->join('lists l', 'l.id', 'moviment.id_list', 'LEFT');

        if ($this->implode) {
            $total->putQuery($this->implode, ' WHERE ');
        }
        return $total->fetch();
    }
}