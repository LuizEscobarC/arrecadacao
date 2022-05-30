<?php

namespace Source\Support\Filters;

use Source\Core\Model;

class FiltersLists extends Filter
{
    public function find(array $columns): array
    {
        foreach ($columns as $column) {
            $arraySelects[] = "{$column}";
        }
        $select = implode(', ', $arraySelects);

        $this->model->find(null, null, $select)
            ->join('hour h', 'list.id_hour', 'h.id')
            ->join('loja s', 'list.id_store', 's.id');
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
            ->join('hour h', 'lists.id_hour', 'h.id')
            ->join('loja s', 'lists.id_store', 's.id');

        if ($this->implode) {
            $total->putQuery($this->implode, ' WHERE ');
        }
        return $total->fetch();
    }
}