<?php

namespace SellNow\Repositories;

use SellNow\Contracts\ProductRepositoryInterface;
use SellNow\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        public Product $product,
    )
    {

    }

    public function findById(int|string $id): ?array
    {
        return $this->product->find($id);
    }

    public function findByParams(array $params, array $relations = []): ?array
    {
        return $this->product->findBy(array_keys($params)[0], array_values($params)[0]);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = []): array
    {
        return $this->product->whereUsingArray($filters);
    }

    public function add(array $data): string
    {
        return $this->product->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->product->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->product->destroy($id);
    }
}
