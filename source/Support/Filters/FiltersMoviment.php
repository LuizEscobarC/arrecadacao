<?php

namespace Source\Support\Filters;

use Source\Models\MoldelInterfaces\FilterInterface;
use Source\Models\Moviment;

class FiltersMoviment extends FilterQuery implements FilterInterface
{

    public function listFilters(?array $filters, array $type, array $keySql): array
    {
        $typeIterator = 0;
        if ($filters = $this->filterSanitaze($filters)) {
            foreach ($filters as $filterKey => $value) {
                if (empty($value)) {
                    $filters[$filterKey] = '';
                    continue;
                }
                $filters[$filterKey] = $value;

                foreach($keySql as $searchName => $key) {
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
        }
        $where = $this->implode();
        // lista os movimentos
        // para pegar o totalizador de valor dinâmico com filtro
        $total = $this->total($where);
        $this->find($where);

        if ($total = $total->fetch()) {
            return [$this->model, $filters, $total];
        } else {
            return [];
        }
    }

    public function total(?string $where): Moviment
    {
        // para pegar o totalizador de valor dinâmico com filtro
        $total = (new Moviment())->find(null, null,
            'sum(new_value) as total_new_value, sum(beat_value) as
             total_beat_value, sum(paying_now) as total_paying_now, 
             sum(expend) as total_expend, sum(get_value) as total_get_value,
              sum(last_value) as total_last_value')
            ->join('hour h', 'moviment.id_hour', 'h.id')
            ->join('loja s', 'moviment.id_store', 's.id');

        if ($where) {
            $total->putQuery($where, ' WHERE ');
        }
        return $total;
    }

    public function find(?string $where): void
    {
        $this->model->find(null, null,
            'moviment.*, h.week_day, h.number_day, h.description as hour, s.nome_loja')
            ->join('hour h', 'h.id', 'moviment.id_hour')
            ->join('loja s', 's.id', 'moviment.id_store');

        if ($where) {
            $this->model->putQuery($where, ' WHERE ');
        }
    }
}