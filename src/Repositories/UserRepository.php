<?php

namespace SellNow\Repositories;

use SellNow\Contracts\UserRepositoryInterface;
use SellNow\Models\Model;

class UserRepository implements UserRepositoryInterface
{

    public function findById(int|string $id): array
    {
        // TODO: Implement findById() method.
    }

    public function getFirstWhere(array $params, array $relations = []): array
    {
        // TODO: Implement getFirstWhere() method.
    }
    public function getListByParams(array $params, array $relations = []): array
    {
        // TODO: Implement getFirstWhere() method.
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