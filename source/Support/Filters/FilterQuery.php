<?php

namespace Source\Support\Filters;

use Source\Core\Model;

/**
 * CLASS FOLLOWING THE SIMPLE RESPONSIBILITY ABOUT ADVANCED FILTER
 */
 abstract class FilterQuery
{
    /**
     * @var
     */
    protected $dataFilter;
    /**
     * @var Model
     */
    protected $model;

    /*
     * @var string|null
     */
    protected $implode;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * FILTER LIKE
     * @param string $column
     * @param string $value
     * @return $this
     */
    protected function like(string $column, string $value): FilterQuery
    {
        $this->dataFilter[] = "{$column} LIKE '%{$value}%'";
        return $this;
    }

    /**
     * BUILD A FILTER SQL
     * @param string $stmt
     * @return $this
     */
    protected function build(string $stmt): FilterQuery
    {
        $this->dataFilter[] = $stmt;
        return $this;
    }

    /**
     * FILTER EQUAL =
     * @param string $column
     * @param string $value
     * @return $this
     */
    protected function equal(string $column, string $value): FilterQuery
    {
        $this->dataFilter[] = "{$column} = {$value}";
        return $this;
    }

    /**
     * FILTER DISTINCT !=
     * @param string $column
     * @param string $value
     * @return $this
     */
    protected function different(string $column, string $value): FilterQuery
    {
        $this->dataFilter[] = "{$column} != {$value}";
        return $this;
    }

    protected function positive(string $column): FilterQuery
    {
        $this->dataFilter[] = "{$column} > 0 ";
        return $this;
    }

    protected function negative(string $column ): FilterQuery
    {
        $this->dataFilter[] = "{$column} < 0 ";
        return $this;
    }

    /**
     * FILTER TO INTERVAL DATES
     * @param string $column
     * @param string $begin
     * @param string $after
     * @return $this
     */
    protected function between(string $column, string $begin, string $after): FilterQuery
    {
        $this->dataFilter[] = "({$column} BETWEEN {$begin} AND {$after})";
        return $this;
    }

    /**
     * IMPLODES ALL ADVANCED FILTERS IF HAVE IT OTHERWISE OUT NULL
     * @param string $operator
     * @return string|null
     */
    protected function implode(string $operator = ' AND '): ?string
    {
        if (empty($this->dataFilter)) {
            return null;
        }
        $this->implode = implode($operator, $this->dataFilter);
        return $this->implode;
    }

    /**
     * SANITAZE THE ARRAY WITH INPUT FILTERS
     * @param array $data
     * @return array
     */
    protected function filterSanitaze(array $data): array
    {
        $data = filter_var_array($data, FILTER_DEFAULT);
        return $data;
    }
}