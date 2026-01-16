<?php

namespace SellNow\Repositories;

use SellNow\Contracts\AuthRepositoryInterface;
use SellNow\Models\Model;
use SellNow\Models\User;

class AuthRepository implements AuthRepositoryInterface
{
    public function __construct(
        public User $user,
    )
    {

    }
    public function findById(int|string $id): ?array
    {
      return  $this->user->find($id);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        // TODO: Implement getFirstWhere() method.
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = []): ?Model
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