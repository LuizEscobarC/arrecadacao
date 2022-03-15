<?php

namespace Source\Models\MoldelInterfaces;

use Source\Core\Model;

interface FilterInterface
{
    public function __construct(Model $model, ?array $filters);

    public function find(array $columns): array;

    public function where(array $type, array $keySql);
}