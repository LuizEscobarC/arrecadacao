<?php

namespace Source\Models\MoldelInterfaces;

use Source\Core\Model;

interface FilterInterface
{
    public function __construct(Model $model);

    public function listFilters(?array $filters, array $type, array $keysSql): array;
}