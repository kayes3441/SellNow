<?php

namespace SellNow\Repositories;

use SellNow\Contracts\CartRepositoryInterface;
use SellNow\Models\Cart;
use SellNow\Models\User;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(
        public Cart $cart,
    )
    {

    }

    public function findById(int|string $id): ?array
    {
        // TODO: Implement findById() method.
    }

    public function findByParams(array $params, array $relations = []): ?array
    {
        return $this->cart->findBy(array_keys($params)[0], array_values($params)[0]);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = []): ?array
    {
       return $this->cart->whereUsingArray($filters);
    }

    public function add(array $data): string
    {
        return $this->cart->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->cart->update(id:$id,data:  $data);
    }

    public function delete(int $id): bool
    {
       return $this->cart->delete($id);
    }
}