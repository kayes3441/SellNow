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

    public function findByParams(array $params, array $relations = []): ?array
    {
        return $this->user->findBy(array_keys($params)[0], array_values($params)[0]);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = []): ?array
    {
        // TODO: Implement getListWhere() method.
    }

    public function add(array $data): string
    {
        return $this->user->create($data);
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