<?php

namespace SellNow\Repositories;

use SellNow\Contracts\PaymentProviderRepositoryInterface;
use SellNow\Models\Model;

class PaymentProviderRepository implements PaymentProviderRepositoryInterface
{

    public function findById(int|string $id): array
    {
        // TODO: Implement findById() method.
    }

    public function findByParams(array $params, array $relations = []): array
    {
        // TODO: Implement findByParams() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = []): array
    {
        // TODO: Implement getListWhere() method.
    }

    public function add(array $data): string|object
    {
        // TODO: Implement add() method.
    }

    public function update(int $id, array $data): bool
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id): bool
    {
        // TODO: Implement delete() method.
    }
}