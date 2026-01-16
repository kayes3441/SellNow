<?php

namespace SellNow\Repositories;

use SellNow\Contracts\OrderRepositoryInterface;
use SellNow\Models\Cart;
use SellNow\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        public Order $order,
    )
    {

    }
    public function findById(int|string $id): ?array
    {
        return $this->order->find($id);
    }

    public function findByParams(array $params, array $relations = []): ?array
    {
        return $this->order->findBy(array_keys($params)[0], array_values($params)[0]);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = []): array
    {
        return $this->order->whereUsingArray($filters);
    }

    public function add(array $data): string
    {
        return $this->order->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->order->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->order->destroy($id);
    }


}
