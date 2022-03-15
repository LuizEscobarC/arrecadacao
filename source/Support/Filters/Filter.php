<?php

namespace Source\Support\Filters;

use Source\Core\Model;
use Source\Models\MoldelInterfaces\FilterInterface;
use Source\Models\Moviment;

Abstract class Filter extends FilterQuery implements FilterInterface
{
    protected $filters;

    public function __construct(Model $model, ?array $filters)
    {
        $this->filters = $filters;
        parent::__construct($model);
    }

    public function where(array $type, array $keySql): Filter
    {
        $typeIterator = 0;
        if ($filters = $this->filterSanitaze($this->filters)) {
            foreach ($filters as $filterKey => $value) {
                if (empty($value)) {
                    $filters[$filterKey] = '';
                    continue;
                }
                $filters[$filterKey] = $value;

                foreach ($keySql as $searchName => $key) {
                    if ($searchName == $filterKey) {
                        $keyWhere = $key;
                    }
                }

                switch ($type[$typeIterator]) {
                    case 'like':
                        $this->like($keyWhere, $value);
                        break;
                    case 'equal':
                        $this->equal($keyWhere, $value);
                        break;
                    case 'different':
                        $this->different($keyWhere, $value);
                        break;
                    case 'between':
                        $this->between('', '', '');
                        break;
                }

                $typeIterator++;
            }

            $this->implode();
        }
        return $this;
    }

    protected function total(array $columnsAndAliases, Model $model = null)
    {
        foreach ($columnsAndAliases as $column => $alias) {
            $arraySelects[] = "sum({$column}) as {$alias}";
        }
        $select = implode(', ', $arraySelects);

        // para pegar o totalizador de valor dinâmico com filtro
        $total = ((!empty($model) ? $model : $this->model))->find(null, null, $select);

        if ($this->implode) {
            $total->putQuery($this->implode, ' WHERE ');
        }
        return $total->fetch();
    }

    public function find(array $columns): array
    {
        foreach ($columns as $column) {
            $arraySelects[] = "{$column}";
        }
        $select = implode(', ', $arraySelects);

        $this->model->find(null, null, $select);
        if ($this->implode) {
            $this->model->putQuery($this->implode, ' WHERE ');
        }
        return [$this->model, $this->filters];
    }
}